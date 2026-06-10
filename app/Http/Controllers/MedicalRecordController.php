<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Medicine;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    /**
     * Show the vital signs form for the Nurse (US-07).
     */
    public function vitalsForm(Queue $queue)
    {
        if (!auth()->user()->isPerawat() && !auth()->user()->isAdmin()) {
            abort(403, 'Hanya Perawat atau Admin yang dapat mengisi tanda vital.');
        }

        // Retrieve or instantiate today's medical record for this queue patient
        $record = MedicalRecord::where('patient_id', $queue->patient_id)
            ->whereDate('created_at', today())
            ->first() ?? new MedicalRecord();

        return view('medical_records.vitals', compact('queue', 'record'));
    }

    /**
     * Store the vital signs input by Perawat.
     */
    public function storeVitals(Request $request, Queue $queue)
    {
        if (!auth()->user()->isPerawat() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'tekanan_darah' => 'required|string',
            'suhu' => 'required|numeric|min:30|max:45',
            'nadi' => 'required|integer|min:30|max:200',
            'berat_badan' => 'required|numeric|min:1|max:300',
            'tinggi_badan' => 'required|numeric|min:10|max:250',
        ]);

        $record = MedicalRecord::where('patient_id', $queue->patient_id)
            ->whereDate('created_at', today())
            ->first();

        if (!$record) {
            $record = new MedicalRecord();
            $record->patient_id = $queue->patient_id;
            $record->doctor_id = $queue->doctor_id;
        }

        $record->tekanan_darah = $request->tekanan_darah;
        $record->suhu = $request->suhu;
        $record->nadi = $request->nadi;
        $record->berat_badan = $request->berat_badan;
        $record->tinggi_badan = $request->tinggi_badan;

        // Placeholders, will be updated by Doctor during consult
        $record->keluhan = $record->keluhan ?? '';
        $record->kode_icd_10 = $record->kode_icd_10 ?? '';
        $record->tindakan_medis = $record->tindakan_medis ?? '';
        
        $record->save();

        return redirect()->route('dashboard')->with('success', 'Tanda-tanda vital berhasil disimpan. Silakan arahkan pasien ke ruang konsultasi dokter.');
    }

    /**
     * Show the Doctor consultation form (US-05, US-06, US-08).
     */
    public function consultForm(Queue $queue)
    {
        if (!auth()->user()->isDokter() && !auth()->user()->isAdmin()) {
            abort(403, 'Hanya Dokter atau Admin yang dapat melakukan konsultasi.');
        }

        // Find today's record (may have vital signs entered by nurse)
        $record = MedicalRecord::where('patient_id', $queue->patient_id)
            ->whereDate('created_at', today())
            ->first() ?? new MedicalRecord();

        // Retrieve chronological medical history (US-08)
        $history = MedicalRecord::where('patient_id', $queue->patient_id)
            ->where('id', '!=', $record->id ?? 0)
            ->with('doctor', 'medicines')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get medicines for selection
        $medicines = Medicine::orderBy('nama', 'asc')->get();

        return view('medical_records.consult', compact('queue', 'record', 'history', 'medicines'));
    }

    /**
     * Store the diagnosis and digital prescription (US-05, US-06).
     */
    public function storeConsult(Request $request, Queue $queue)
    {
        if (!auth()->user()->isDokter() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'keluhan' => 'required|string',
            'kode_icd_10' => 'required|string|max:10',
            'tindakan_medis' => 'required|string',
            // Prescription validation
            'prescriptions' => 'nullable|array',
            'prescriptions.*.medicine_id' => 'required|exists:medicines,id',
            'prescriptions.*.dosis' => 'required|string|max:255',
            'prescriptions.*.jumlah' => 'required|integer|min:1',
            // Vitals fallbacks (in case doctor needs to edit or nurse hasn't filled it)
            'tekanan_darah' => 'required|string',
            'suhu' => 'required|numeric',
            'nadi' => 'required|integer',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request, $queue) {
            // Find or create medical record
            $record = MedicalRecord::where('patient_id', $queue->patient_id)
                ->whereDate('created_at', today())
                ->first();

            if (!$record) {
                $record = new MedicalRecord();
                $record->patient_id = $queue->patient_id;
                $record->doctor_id = $queue->doctor_id;
            }

            // Fill all fields
            $record->tekanan_darah = $request->tekanan_darah;
            $record->suhu = $request->suhu;
            $record->nadi = $request->nadi;
            $record->berat_badan = $request->berat_badan;
            $record->tinggi_badan = $request->tinggi_badan;
            $record->keluhan = $request->keluhan;
            $record->kode_icd_10 = strtoupper($request->kode_icd_10);
            $record->tindakan_medis = $request->tindakan_medis;
            
            $record->save();

            // Save digital prescriptions (pivot table)
            // Detach previous to prevent duplicate on re-submission
            $record->medicines()->detach();

            if ($request->has('prescriptions')) {
                foreach ($request->prescriptions as $presc) {
                    $record->medicines()->attach($presc['medicine_id'], [
                        'dosis' => $presc['dosis'],
                        'jumlah' => $presc['jumlah']
                    ]);
                }
            }

            // Update Queue Status to 'Diperiksa' (examined, waiting for medicine dispensing)
            $queue->update([
                'status' => 'Diperiksa'
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Pemeriksaan medis dan resep berhasil disimpan. Pasien diarahkan ke apotek.');
    }
}
