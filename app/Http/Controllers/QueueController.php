<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\User;
use App\Models\Patient;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QueueController extends Controller
{
    /**
     * Show the queue booking form (US-09).
     */
    public function bookForm()
    {
        $user = auth()->user();
        
        // If logged in as Pasien, load their patient profile
        $patient = null;
        if ($user->isPasien()) {
            $patient = $user->patient;
            if (!$patient) {
                return redirect()->route('dashboard')->with('error', 'Profil pasien Anda belum lengkap. Silakan hubungi admin.');
            }
        }

        // Get list of doctors
        $doctors = User::where('role', 'Dokter')->with('schedules')->get();
        
        // Get list of all active patients (for Resepsionis/Admin who books on behalf of a patient)
        $patients = Patient::orderBy('nama', 'asc')->get();

        return view('queues.book', compact('doctors', 'patient', 'patients'));
    }

    /**
     * Store the booked queue in the database (US-09).
     */
    public function storeBook(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'patient_id' => $user->isPasien() ? 'nullable' : 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'sesi' => 'required|in:Pagi,Siang,Sore',
        ]);

        // Determine patient_id
        if ($user->isPasien()) {
            $patient = $user->patient;
            if (!$patient) {
                return back()->withErrors(['patient_id' => 'Profil pasien tidak ditemukan.'])->withInput();
            }
            $patientId = $patient->id;
        } else {
            $patientId = $request->patient_id;
        }

        $tanggal = $request->tanggal;
        $doctorId = $request->doctor_id;
        $sesi = $request->sesi;

        // 1. Block booking if outside doctor's schedule day
        $dayOfWeekMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $dayNameEn = Carbon::parse($tanggal)->format('l');
        $indoDayName = $dayOfWeekMap[$dayNameEn];

        $schedule = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('hari', $indoDayName)
            ->first();

        if (!$schedule) {
            return back()->withErrors(['tanggal' => "Dokter tidak memiliki jadwal praktek pada hari {$indoDayName}."])->withInput();
        }

        // 2. Block booking if schedule capacity is full (Limit: 10 per session)
        $currentBookings = Queue::where('doctor_id', $doctorId)
            ->whereDate('tanggal', $tanggal)
            ->where('sesi', $sesi)
            ->count();

        if ($currentBookings >= 10) {
            return back()->withErrors(['sesi' => 'Kuota antrean dokter untuk sesi ini sudah penuh (maksimum 10 pasien). Silakan pilih sesi atau hari lain.'])->withInput();
        }

        // 3. Auto-generate nomor_antrean (Format: Prefix-001)
        // Match prefix based on doctor name/Poli
        $doctor = User::find($doctorId);
        $prefix = 'K'; // Default Klinik
        if (str_contains(strtolower($doctor->name), 'umum')) {
            $prefix = 'U';
        } elseif (str_contains(strtolower($doctor->name), 'anak')) {
            $prefix = 'A';
        } elseif (str_contains(strtolower($doctor->name), 'gigi')) {
            $prefix = 'G';
        }

        $lastQueue = Queue::where('doctor_id', $doctorId)
            ->whereDate('tanggal', $tanggal)
            ->where('nomor_antrean', 'LIKE', "{$prefix}-%")
            ->orderBy('nomor_antrean', 'desc')
            ->first();

        if ($lastQueue) {
            $lastNum = (int) substr($lastQueue->nomor_antrean, -3);
            $nextNum = str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNum = '001';
        }

        $nomorAntrean = "{$prefix}-{$nextNum}";

        // Check if patient already booked same doctor on same day
        $duplicate = Queue::where('patient_id', $patientId)
            ->where('doctor_id', $doctorId)
            ->whereDate('tanggal', $tanggal)
            ->exists();

        if ($duplicate) {
            return back()->withErrors(['tanggal' => 'Anda sudah terdaftar di antrean dokter ini pada tanggal tersebut.'])->withInput();
        }

        // Create the queue ticket
        Queue::create([
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'nomor_antrean' => $nomorAntrean,
            'tanggal' => $tanggal,
            'sesi' => $sesi,
            'status' => 'Menunggu',
        ]);

        $message = "Antrean berhasil dipesan! Nomor antrean Anda adalah: {$nomorAntrean}";
        if ($user->isPasien()) {
            return redirect()->route('dashboard')->with('success', $message);
        } else {
            return redirect()->route('queues.monitor')->with('success', $message);
        }
    }

    /**
     * Show the Queue Monitoring Dashboard (US-10).
     */
    public function monitor(Request $request)
    {
        $today = today();
        
        $queues = Queue::whereDate('tanggal', $today)
            ->with('patient', 'doctor')
            ->orderBy('nomor_antrean', 'asc')
            ->get();

        return view('queues.monitor', compact('queues'));
    }

    /**
     * Update the queue ticket status (US-10).
     */
    public function updateStatus(Request $request, Queue $queue)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diperiksa,Selesai,Batal',
        ]);

        $queue->update([
            'status' => $request->status
        ]);

        return back()->with('success', "Status antrean {$queue->nomor_antrean} berhasil diubah menjadi {$request->status}.");
    }
}
