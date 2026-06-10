<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicalRecord;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class MedicineController extends Controller
{
    /**
     * Display a listing of medicines (US-15 & US-16).
     */
    public function index()
    {
        $medicines = Medicine::orderBy('nama', 'asc')->paginate(15);
        return view('medicines.index', compact('medicines'));
    }

    /**
     * Show the form for creating a new medicine.
     */
    public function create()
    {
        return view('medicines.create');
    }

    /**
     * Store a newly created medicine in database (US-16).
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'satuan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0', // Reject negative price
            'stok' => 'required|integer|min:0',   // Reject negative stock
            'min_stock' => 'required|integer|min:0',
        ], [
            'harga.min' => 'Harga obat tidak boleh bernilai negatif.',
            'stok.min' => 'Stok obat tidak boleh bernilai negatif.',
            'min_stock.min' => 'Minimum stok tidak boleh bernilai negatif.',
        ]);

        Medicine::create($request->all());

        return redirect()->route('medicines.index')->with('success', 'Obat baru berhasil ditambahkan ke inventaris.');
    }

    /**
     * Show the form for editing the specified medicine.
     */
    public function edit(Medicine $medicine)
    {
        return view('medicines.edit', compact('medicine'));
    }

    /**
     * Update the specified medicine in database.
     */
    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'satuan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
        ], [
            'harga.min' => 'Harga obat tidak boleh bernilai negatif.',
            'stok.min' => 'Stok obat tidak boleh bernilai negatif.',
            'min_stock.min' => 'Minimum stok tidak boleh bernilai negatif.',
        ]);

        $medicine->update($request->all());

        return redirect()->route('medicines.index')->with('success', 'Data obat berhasil diperbarui.');
    }

    /**
     * Remove the specified medicine from database.
     */
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('medicines.index')->with('success', 'Obat berhasil dihapus dari inventaris.');
    }

    /**
     * Display pending prescriptions for Apoteker (US-13).
     */
    public function prescriptionsIndex()
    {
        // Get today's queues that are in 'Diperiksa' status (consulted, waiting for medicine)
        $today = today();
        
        $pendingQueues = Queue::where('status', 'Diperiksa')
            ->whereDate('tanggal', $today)
            ->with('patient', 'doctor')
            ->get();

        // Retrieve the medical records corresponding to these queues
        // For each queue, we find the MedicalRecord of that patient created today
        $prescriptions = [];
        foreach ($pendingQueues as $queue) {
            $record = MedicalRecord::where('patient_id', $queue->patient_id)
                ->whereDate('created_at', $today)
                ->with('medicines')
                ->first();

            if ($record && $record->medicines->isNotEmpty()) {
                $prescriptions[] = [
                    'queue' => $queue,
                    'record' => $record,
                ];
            }
        }

        return view('pharmacy.index', compact('prescriptions'));
    }

    /**
     * Dispense medicines and deduct stock atomically (US-14).
     */
    public function dispensePrescription(Request $request, $recordId)
    {
        $record = MedicalRecord::findOrFail($recordId);

        // Find the today's queue for this patient and doctor with status 'Diperiksa'
        $queue = Queue::where('patient_id', $record->patient_id)
            ->where('doctor_id', $record->doctor_id)
            ->whereDate('tanggal', today())
            ->where('status', 'Diperiksa')
            ->first();

        if (!$queue) {
            return back()->with('error', 'Antrean aktif untuk resep ini tidak ditemukan.');
        }

        try {
            DB::transaction(function () use ($record, $queue) {
                // Check stock for all prescribed medicines first
                foreach ($record->medicines as $prescribedMed) {
                    // Lock the row for update to prevent concurrent race conditions
                    $medicine = Medicine::where('id', $prescribedMed->id)->lockForUpdate()->first();
                    $qtyNeeded = $prescribedMed->pivot->jumlah;

                    if ($medicine->stok < $qtyNeeded) {
                        // Throw exception to trigger rollback
                        throw new Exception("Stok obat '{$medicine->nama}' tidak mencukupi! Stok saat ini: {$medicine->stok}, dibutuhkan: {$qtyNeeded}.");
                    }
                }

                // Decrement stock if all check passed
                foreach ($record->medicines as $prescribedMed) {
                    $medicine = Medicine::find($prescribedMed->id);
                    $medicine->decrement('stok', $prescribedMed->pivot->jumlah);
                }

                // Update queue status to Selesai
                $queue->update([
                    'status' => 'Selesai'
                ]);
            });

            return redirect()->route('pharmacy.prescriptions')->with('success', "Resep untuk pasien {$queue->patient->nama} berhasil diproses. Obat telah diserahkan.");
        
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
