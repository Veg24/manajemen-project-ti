<?php

namespace App\Http\Controllers;

use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of doctor schedules.
     */
    public function index()
    {
        $schedules = DoctorSchedule::with('doctor')->orderBy('doctor_id')->orderBy('hari')->paginate(15);
        return view('schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new doctor schedule.
     */
    public function create()
    {
        $doctors = User::where('role', 'Dokter')->get();
        return view('schedules.create', compact('doctors'));
    }

    /**
     * Store a newly created doctor schedule in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // Validate double entry for the same doctor, same day
        $exists = DoctorSchedule::where('doctor_id', $request->doctor_id)
            ->where('hari', $request->hari)
            ->exists();

        if ($exists) {
            return back()->withErrors(['hari' => 'Dokter tersebut sudah memiliki jadwal untuk hari ini. Silakan ubah jadwal yang sudah ada.'])->withInput();
        }

        DoctorSchedule::create($request->all());

        return redirect()->route('doctor-schedules.index')->with('success', 'Jadwal praktek dokter berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified doctor schedule.
     */
    public function edit($id)
    {
        $schedule = DoctorSchedule::findOrFail($id);
        $doctors = User::where('role', 'Dokter')->get();
        return view('schedules.edit', compact('schedule', 'doctors'));
    }

    /**
     * Update the specified doctor schedule in database.
     */
    public function update(Request $request, $id)
    {
        $schedule = DoctorSchedule::findOrFail($id);

        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // Validate double entry (excluding this record)
        $exists = DoctorSchedule::where('doctor_id', $request->doctor_id)
            ->where('hari', $request->hari)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['hari' => 'Dokter tersebut sudah memiliki jadwal untuk hari ini.'])->withInput();
        }

        $schedule->update($request->all());

        return redirect()->route('doctor-schedules.index')->with('success', 'Jadwal praktek dokter berhasil diperbarui.');
    }

    /**
     * Remove the specified doctor schedule from database.
     */
    public function destroy($id)
    {
        $schedule = DoctorSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('doctor-schedules.index')->with('success', 'Jadwal praktek dokter berhasil dihapus.');
    }
}
