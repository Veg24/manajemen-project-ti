<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Laporan Statistik & Keuangan Klinik') }}
            </h2>
            <!-- Export PDF Link -->
            <a href="{{ route('reports.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="inline-flex items-center px-4 py-2 bg-red-650 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider rounded transition shadow-sm">
                <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Unduh Laporan PDF
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Date Filter Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col md:flex-row md:items-end gap-4">
                        <div class="flex-1">
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        </div>
                        <div class="flex-1">
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Selesai</label>
                            <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                            Terapkan Filter
                        </button>
                    </form>
                </div>
            </div>

            <!-- Financial Summary Cards (US-18) -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">Total Kunjungan</span>
                    <span class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mt-2 block">{{ count($financialDetails) }}</span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">Pendapatan Jasa Konsultasi</span>
                    <span class="text-xl font-bold text-gray-800 dark:text-gray-250 mt-2 block">Rp{{ number_format($totalConsultations, 0, ',', '.') }}</span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">Pendapatan Tindakan Medis</span>
                    <span class="text-xl font-bold text-gray-800 dark:text-gray-250 mt-2 block">Rp{{ number_format($totalActions, 0, ',', '.') }}</span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 bg-gradient-to-br from-green-50 to-white dark:from-green-950/20 dark:to-gray-800">
                    <span class="text-xs text-green-700 dark:text-green-400 font-bold uppercase tracking-wider block">Total Pendapatan Kotor</span>
                    <span class="text-2xl font-extrabold text-green-600 dark:text-green-400 mt-2 block">Rp{{ number_format($grandTotalRevenue, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Graphic Charts Section (US-17 & US-19) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Visits Chart (Line) -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700 flex flex-col">
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-4">Grafik Kunjungan Harian (Real-Time)</h3>
                    <div class="flex-1 min-h-[300px]">
                        <canvas id="visitsChart"></canvas>
                    </div>
                </div>

                <!-- Diagnosis Pie Chart -->
                <div class="lg:col-span-1 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700 flex flex-col">
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-4">Top 5 ICD-10 Diagnoses</h3>
                    @if($topDiagnoses->isEmpty())
                        <div class="flex-1 flex items-center justify-center text-gray-400 text-xs">Belum ada diagnosis medis pada range tanggal ini.</div>
                    @else
                        <div class="flex-1 flex items-center justify-center min-h-[220px]">
                            <div class="w-full max-w-[200px]">
                                <canvas id="diagnosesPieChart"></canvas>
                            </div>
                        </div>
                        <div class="mt-4 space-y-1.5 text-xs text-gray-600 dark:text-gray-400">
                            @foreach($topDiagnoses as $index => $diag)
                                <div class="flex justify-between">
                                    <span class="font-semibold">{{ $index+1 }}. {{ $diag->kode_icd_10 }}</span>
                                    <span class="font-bold text-indigo-600">{{ $diag->total }} Kasus</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Financial Table Detail (US-18) -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Rincian Transaksi Keuangan</h3>
                    <p class="text-xs text-gray-500 mt-1">Daftar lengkap pemasukan per rekam medis pasien yang terlayani.</p>
                </div>
                <div class="p-6">
                    @if(empty($financialDetails))
                        <div class="text-center py-8 text-gray-400">Tidak ada rincian transaksi keuangan pada periode ini.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-900/50 font-semibold text-gray-500">
                                    <tr>
                                        <th class="px-6 py-3 text-left">Tanggal</th>
                                        <th class="px-6 py-3 text-left">Pasien</th>
                                        <th class="px-6 py-3 text-left">Dokter Pemeriksa</th>
                                        <th class="px-6 py-3 text-left">Jasa Konsul</th>
                                        <th class="px-6 py-3 text-left">Tindakan Medis</th>
                                        <th class="px-6 py-3 text-left">Obat Apotek</th>
                                        <th class="px-6 py-3 text-right">Total Transaksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($financialDetails as $detail)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                                            <td class="px-6 py-4">{{ $detail['date'] }}</td>
                                            <td class="px-6 py-4 font-semibold">{{ $detail['patient'] }}</td>
                                            <td class="px-6 py-4 text-gray-500">{{ $detail['doctor'] }}</td>
                                            <td class="px-6 py-4">Rp{{ number_format($detail['consult_fee'], 0, ',', '.') }}</td>
                                            <td class="px-6 py-4">Rp{{ number_format($detail['action_fee'], 0, ',', '.') }}</td>
                                            <td class="px-6 py-4">Rp{{ number_format($detail['medicine_cost'], 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-gray-100">Rp{{ number_format($detail['total'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-900/50 font-bold border-t border-gray-200 dark:border-gray-700">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right text-base uppercase">Total Keseluruhan:</td>
                                        <td class="px-6 py-4 text-xs">Rp{{ number_format($totalConsultations, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-xs">Rp{{ number_format($totalActions, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-xs">Rp{{ number_format($totalMedicinesCost, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-right text-base text-green-600 dark:text-green-400">Rp{{ number_format($grandTotalRevenue, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js Libraries and Rendering -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Line Chart: Daily Visits (US-17)
            const ctxVisits = document.getElementById('visitsChart').getContext('2d');
            new Chart(ctxVisits, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Jumlah Kunjungan',
                            data: {!! json_encode($chartVisits) !!},
                            borderColor: '#4f46e5', // indigo
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            yAxisID: 'yVisits',
                            tension: 0.3
                        },
                        {
                            label: 'Pendapatan (Rupiah)',
                            data: {!! json_encode($chartRevenue) !!},
                            borderColor: '#10b981', // green
                            backgroundColor: 'rgba(16, 185, 129, 0.05)',
                            fill: false,
                            yAxisID: 'yRevenue',
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yVisits: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Jumlah Kunjungan Pasien'
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        yRevenue: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Pendapatan (Rp)'
                            },
                            grid: {
                                drawOnChartArea: false // only want the grid lines for one axis
                            }
                        }
                    }
                }
            });

            // 2. Pie Chart: Diagnoses (US-19)
            @if(!$topDiagnoses->isEmpty())
                const ctxDiag = document.getElementById('diagnosesPieChart').getContext('2d');
                new Chart(ctxDiag, {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode($topDiagnoses->pluck('kode_icd_10')) !!},
                        datasets: [{
                            data: {!! json_encode($topDiagnoses->pluck('total')) !!},
                            backgroundColor: [
                                '#6366f1', // indigo
                                '#3b82f6', // blue
                                '#10b981', // green
                                '#f59e0b', // amber
                                '#ef4444'  // red
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            @endif
        });
    </script>
</x-app-layout>
