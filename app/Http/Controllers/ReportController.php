<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Display report page with financial tables and Chart.js datasets (US-17, US-18, US-19).
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Default to last 30 days
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        // Query medical records in date range
        $records = MedicalRecord::whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->when($user->isDokter(), function ($q) use ($user) {
                $q->where('doctor_id', $user->id);
            })
            ->with('patient', 'doctor', 'medicines')
            ->orderBy('created_at', 'asc')
            ->get();

        // 1. Process daily visits & revenue data for Chart.js
        $dailyStats = [];
        $tempDate = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Pre-fill daily array to ensure dates with 0 visits are plotted correctly
        while ($tempDate->lte($end)) {
            $formattedDate = $tempDate->format('d-m-Y');
            $dailyStats[$formattedDate] = [
                'visits' => 0,
                'revenue' => 0
            ];
            $tempDate->addDay();
        }

        // Calculate financials and aggregate per date
        // Consultation Fee: Rp50,000
        // Action Fee: Rp25,050
        // Medicine Cost: Dynamic sum from pivot
        $financialDetails = [];
        $totalConsultations = 0;
        $totalActions = 0;
        $totalMedicinesCost = 0;
        $grandTotalRevenue = 0;

        foreach ($records as $record) {
            $recordDate = Carbon::parse($record->created_at)->format('d-m-Y');
            
            // Medicine Sales
            $medCost = 0;
            foreach ($record->medicines as $med) {
                $medCost += $med->pivot->jumlah * $med->harga;
            }

            $consultFee = 50000;
            $actionFee = 25000;
            $totalRecordRevenue = $consultFee + $actionFee + $medCost;

            // Aggregating totals
            $totalConsultations += $consultFee;
            $totalActions += $actionFee;
            $totalMedicinesCost += $medCost;
            $grandTotalRevenue += $totalRecordRevenue;

            // Add to Chart.js dataset
            if (isset($dailyStats[$recordDate])) {
                $dailyStats[$recordDate]['visits'] += 1;
                $dailyStats[$recordDate]['revenue'] += $totalRecordRevenue;
            }

            // Save details for the table view
            $financialDetails[] = [
                'date' => Carbon::parse($record->created_at)->format('d-m-Y H:i'),
                'patient' => $record->patient->nama,
                'doctor' => $record->doctor->name,
                'consult_fee' => $consultFee,
                'action_fee' => $actionFee,
                'medicine_cost' => $medCost,
                'total' => $totalRecordRevenue
            ];
        }

        // Extract labels and values for Chart.js
        $chartLabels = array_keys($dailyStats);
        $chartVisits = array_column($dailyStats, 'visits');
        $chartRevenue = array_column($dailyStats, 'revenue');

        // 2. Query top 5 diagnoses for Pie Chart (US-19)
        $topDiagnoses = MedicalRecord::select('kode_icd_10', DB::raw('count(*) as total'))
            ->when($user->isDokter(), function ($q) use ($user) {
                $q->where('doctor_id', $user->id);
            })
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->groupBy('kode_icd_10')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return view('reports.index', compact(
            'startDate',
            'endDate',
            'financialDetails',
            'totalConsultations',
            'totalActions',
            'totalMedicinesCost',
            'grandTotalRevenue',
            'chartLabels',
            'chartVisits',
            'chartRevenue',
            'topDiagnoses'
        ));
    }

    /**
     * Export reports to PDF (US-20).
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $records = MedicalRecord::whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->when($user->isDokter(), function ($q) use ($user) {
                $q->where('doctor_id', $user->id);
            })
            ->with('patient', 'doctor', 'medicines')
            ->orderBy('created_at', 'asc')
            ->get();

        $financialDetails = [];
        $totalConsultations = 0;
        $totalActions = 0;
        $totalMedicinesCost = 0;
        $grandTotalRevenue = 0;

        foreach ($records as $record) {
            $medCost = 0;
            foreach ($record->medicines as $med) {
                $medCost += $med->pivot->jumlah * $med->harga;
            }

            $consultFee = 50000;
            $actionFee = 25000;
            $totalRecordRevenue = $consultFee + $actionFee + $medCost;

            $totalConsultations += $consultFee;
            $totalActions += $actionFee;
            $totalMedicinesCost += $medCost;
            $grandTotalRevenue += $totalRecordRevenue;

            $financialDetails[] = [
                'date' => Carbon::parse($record->created_at)->format('d-m-Y H:i'),
                'patient' => $record->patient->nama,
                'doctor' => $record->doctor->name,
                'consult_fee' => $consultFee,
                'action_fee' => $actionFee,
                'medicine_cost' => $medCost,
                'total' => $totalRecordRevenue
            ];
        }

        $printDate = Carbon::now()->format('d-m-Y H:i');

        // Generate PDF using Barryvdh/DomPDF Facade
        $pdf = Pdf::loadView('reports.pdf', compact(
            'startDate',
            'endDate',
            'financialDetails',
            'totalConsultations',
            'totalActions',
            'totalMedicinesCost',
            'grandTotalRevenue',
            'printDate'
        ));

        return $pdf->download("laporan-klinik-{$startDate}-to-{$endDate}.pdf");
    }
}
