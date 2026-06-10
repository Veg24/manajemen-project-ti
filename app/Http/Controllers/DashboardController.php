<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Medicine;
use App\Models\Queue;
use App\Models\MedicalRecord;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the central dashboard depending on user role.
     */
    public function index()
    {
        $user = auth()->user();
        $today = today();

        // 1. ADMIN DASHBOARD DATA
        if ($user->isAdmin()) {
            $totalPatients = Patient::count();
            $totalMedicines = Medicine::count();
            $todayQueuesCount = Queue::whereDate('tanggal', $today)->count();
            
            // Low stock medicines (US-15)
            $lowStockMedicines = Medicine::whereRaw('stok <= min_stock')->get();
            $lowStockCount = $lowStockMedicines->count();

            // Financial Summary (US-18) - Sum of medicine sales, consultation fees, and medical actions
            // Let's assume consultation fee is a flat 50,000 IDR and medical action is calculated/simulated.
            // Or we can just calculate aggregate financial data from real DB records.
            // Let's calculate: total income from medicines (price * quantity in medical_record_medicine) 
            // plus flat consultation fee (e.g., 50.000 per medical record) plus any action fee.
            $totalConsultations = MedicalRecord::count();
            $consultationIncome = $totalConsultations * 50000;
            
            $medicineIncome = DB::table('medical_record_medicine')
                ->join('medicines', 'medical_record_medicine.medicine_id', '=', 'medicines.id')
                ->sum(DB::raw('medical_record_medicine.jumlah * medicines.harga'));
                
            $totalIncome = $consultationIncome + $medicineIncome;

            return view('dashboard', compact(
                'totalPatients', 
                'totalMedicines', 
                'todayQueuesCount', 
                'lowStockCount', 
                'lowStockMedicines',
                'totalIncome'
            ));
        }

        // 2. RESEPSIONIS DASHBOARD DATA
        if ($user->isResepsionis()) {
            $totalPatients = Patient::count();
            $todayQueues = Queue::whereDate('tanggal', $today)
                ->with('patient', 'doctor')
                ->orderBy('nomor_antrean', 'asc')
                ->get();
            $waitingCount = $todayQueues->where('status', 'Menunggu')->count();

            return view('dashboard', compact('totalPatients', 'todayQueues', 'waitingCount'));
        }

        // 3. DOKTER DASHBOARD DATA
        if ($user->isDokter()) {
            // Get today's queues assigned to this doctor
            $todayQueues = Queue::where('doctor_id', $user->id)
                ->whereDate('tanggal', $today)
                ->with('patient')
                ->orderBy('nomor_antrean', 'asc')
                ->get();

            // Diagnose stats (top 5 ICD-10 diagnoses for Pie Chart)
            $topDiagnoses = MedicalRecord::where('doctor_id', $user->id)
                ->select('kode_icd_10', DB::raw('count(*) as total'))
                ->groupBy('kode_icd_10')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();

            return view('dashboard', compact('todayQueues', 'topDiagnoses'));
        }

        // 4. PERAWAT DASHBOARD DATA
        if ($user->isPerawat()) {
            // Get today's queues needing vital signs input
            $todayQueues = Queue::whereDate('tanggal', $today)
                ->with('patient', 'doctor')
                ->orderBy('nomor_antrean', 'asc')
                ->get();

            return view('dashboard', compact('todayQueues'));
        }

        // 5. APOTEKER DASHBOARD DATA
        if ($user->isApoteker()) {
            // Pending prescriptions are medical records associated with queues that are in 'Diperiksa' (examined by doctor) status
            $pendingQueues = Queue::where('status', 'Diperiksa')
                ->whereDate('tanggal', $today)
                ->with('patient', 'doctor')
                ->get();

            return view('dashboard', compact('pendingQueues'));
        }

        // 6. PASIEN DASHBOARD DATA
        if ($user->isPasien()) {
            $patient = $user->patient;
            
            if (!$patient) {
                // If patient profile is missing, redirect them to complete it or show a notice
                return view('dashboard', ['no_profile' => true]);
            }

            // Get patient's today queue tickets
            $myQueues = Queue::where('patient_id', $patient->id)
                ->whereDate('tanggal', $today)
                ->with('doctor')
                ->get();

            // Queue alert logic (US-12): Check if there is an active queue ticket, and count how many people are in front of them
            $queueAlerts = [];
            foreach ($myQueues as $q) {
                if ($q->status === 'Menunggu') {
                    // Count how many people are waiting with a smaller queue number for the same doctor on the same date
                    $peopleInFront = Queue::where('doctor_id', $q->doctor_id)
                        ->whereDate('tanggal', $today)
                        ->where('status', 'Menunggu')
                        ->where('nomor_antrean', '<', $q->nomor_antrean)
                        ->count();
                    
                    $queueAlerts[] = [
                        'queue' => $q,
                        'people_in_front' => $peopleInFront
                    ];
                }
            }

            return view('dashboard', compact('patient', 'myQueues', 'queueAlerts'));
        }

        return view('dashboard');
    }
}
