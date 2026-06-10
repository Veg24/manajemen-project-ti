<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }} - Portal {{ auth()->user()->role }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- ============================================== -->
            <!-- 1. ADMIN DASHBOARD VIEW                        -->
            <!-- ============================================== -->
            @if(auth()->user()->isAdmin())
                <div class="space-y-8">
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">Total Pasien</span>
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mt-2 block">{{ $totalPatients }}</span>
                            <a href="{{ route('patients.index') }}" class="text-xs text-indigo-500 hover:text-indigo-600 mt-3 inline-block font-semibold">Kelola Pasien &rarr;</a>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">Total Master Obat</span>
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mt-2 block">{{ $totalMedicines }}</span>
                            <a href="{{ route('medicines.index') }}" class="text-xs text-indigo-500 hover:text-indigo-600 mt-3 inline-block font-semibold">Kelola Obat &rarr;</a>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">Antrean Hari Ini</span>
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mt-2 block">{{ $todayQueuesCount }}</span>
                            <a href="{{ route('queues.monitor') }}" class="text-xs text-indigo-500 hover:text-indigo-600 mt-3 inline-block font-semibold">Monitor Antrean &rarr;</a>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">Estimasi Pendapatan</span>
                            <span class="text-3xl font-extrabold text-green-600 dark:text-green-400 mt-2 block">Rp{{ number_format($totalIncome, 0, ',', '.') }}</span>
                            <a href="{{ route('reports.index') }}" class="text-xs text-indigo-500 hover:text-indigo-600 mt-3 inline-block font-semibold">Laporan Keuangan &rarr;</a>
                        </div>
                    </div>

                    <!-- Low Stock Alert Badge (US-15) -->
                    @if($lowStockCount > 0)
                        <div class="p-5 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-lg shadow-sm">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <h4 class="text-sm font-bold text-red-800 dark:text-red-300">Pemberitahuan Stok Obat Menipis!</h4>
                                    <p class="text-xs text-red-700 dark:text-red-400 mt-0.5">Terdapat {{ $lowStockCount }} obat yang memiliki stok kurang dari atau sama dengan batas minimum stock.</p>
                                </div>
                            </div>
                            <div class="mt-4 overflow-x-auto">
                                <table class="min-w-full divide-y divide-red-200 dark:divide-red-900/30 text-xs text-red-800 dark:text-red-300">
                                    <thead>
                                        <tr>
                                            <th class="text-left font-semibold py-1">Nama Obat</th>
                                            <th class="text-left font-semibold py-1">Stok Saat Ini</th>
                                            <th class="text-left font-semibold py-1">Min. Stok</th>
                                            <th class="text-right py-1">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-red-100 dark:divide-red-900/10">
                                        @foreach($lowStockMedicines as $med)
                                            <tr>
                                                <td class="py-1.5 font-medium">{{ $med->nama }}</td>
                                                <td class="py-1.5 font-bold">{{ $med->stok }} {{ $med->satuan }}</td>
                                                <td class="py-1.5">{{ $med->min_stock }} {{ $med->satuan }}</td>
                                                <td class="py-1.5 text-right">
                                                    <a href="{{ route('medicines.edit', $med->id) }}" class="underline font-semibold text-red-900 dark:text-red-200 hover:text-red-700">Tambah Stok</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Quick Admin Menu -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Navigasi Cepat Administrasi</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <a href="{{ route('patients.index') }}" class="p-4 bg-indigo-50 dark:bg-indigo-950/20 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-950/40 rounded-xl text-center font-bold text-sm transition">
                                Kelola Pasien
                            </a>
                            <a href="{{ route('medicines.index') }}" class="p-4 bg-indigo-50 dark:bg-indigo-950/20 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-950/40 rounded-xl text-center font-bold text-sm transition">
                                Kelola Apotek / Obat
                            </a>
                            <a href="{{ route('doctor-schedules.index') }}" class="p-4 bg-indigo-50 dark:bg-indigo-950/20 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-950/40 rounded-xl text-center font-bold text-sm transition">
                                Jadwal Dokter
                            </a>
                            <a href="{{ route('reports.index') }}" class="p-4 bg-indigo-50 dark:bg-indigo-950/20 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-950/40 rounded-xl text-center font-bold text-sm transition">
                                Laporan Klinik
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- ============================================== -->
            <!-- 2. RESEPSIONIS DASHBOARD VIEW                  -->
            <!-- ============================================== -->
            @if(auth()->user()->isResepsionis())
                <div class="space-y-8">
                    <!-- Resepsionis Action Bar -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                            <span class="text-sm font-semibold text-gray-400">Total Pasien Terdaftar</span>
                            <span class="text-4xl font-extrabold text-gray-900 dark:text-gray-100 block mt-2">{{ $totalPatients }}</span>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center flex flex-col justify-center">
                            <a href="{{ route('patients.create') }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition">
                                + Daftarkan Pasien Baru
                            </a>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center flex flex-col justify-center">
                            <a href="{{ route('queues.book') }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition">
                                + Registrasi Antrean Pasien
                            </a>
                        </div>
                    </div>

                    <!-- Queue Monitor (US-10) -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Antrean Kunjungan Hari Ini</h3>
                                <p class="text-xs text-gray-500 mt-1">Halaman memonitor dan mengubah status antrean pasien (Menunggu &rarr; Diperiksa &rarr; Selesai &rarr; Batal)</p>
                            </div>
                            <a href="{{ route('queues.monitor') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:outline-none transition">
                                Monitor Selengkapnya
                            </a>
                        </div>
                        <div class="p-6">
                            @if($todayQueues->isEmpty())
                                <div class="text-center py-8 text-gray-500">Belum ada antrean yang terdaftar untuk hari ini.</div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                                            <tr>
                                                <th class="px-6 py-3 text-left font-semibold text-gray-500">No. Antrean</th>
                                                <th class="px-6 py-3 text-left font-semibold text-gray-500">Pasien</th>
                                                <th class="px-6 py-3 text-left font-semibold text-gray-500">Dokter</th>
                                                <th class="px-6 py-3 text-left font-semibold text-gray-500">Sesi</th>
                                                <th class="px-6 py-3 text-left font-semibold text-gray-500">Status</th>
                                                <th class="px-6 py-3 text-right font-semibold text-gray-500">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($todayQueues as $q)
                                                <tr>
                                                    <td class="px-6 py-4 font-bold">{{ $q->nomor_antrean }}</td>
                                                    <td class="px-6 py-4">{{ $q->patient->nama }}</td>
                                                    <td class="px-6 py-4">{{ $q->doctor->name }}</td>
                                                    <td class="px-6 py-4">{{ $q->sesi }}</td>
                                                    <td class="px-6 py-4">
                                                        @php
                                                            $statusColors = [
                                                                'Menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                                                'Diperiksa' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                                                'Selesai' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                                                'Batal' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                                            ];
                                                            $color = $statusColors[$q->status] ?? 'bg-gray-100 text-gray-800';
                                                        @endphp
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $color }}">
                                                            {{ $q->status }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 text-right">
                                                        @if($q->status === 'Menunggu')
                                                            <form method="POST" action="{{ route('queues.update-status', $q->id) }}" class="inline-block">
                                                                @csrf
                                                                <input type="hidden" name="status" value="Batal">
                                                                <button type="submit" class="text-xs text-red-500 font-bold hover:underline">Batalkan</button>
                                                            </form>
                                                        @else
                                                            <span class="text-gray-400 italic text-xs">Selesai/Diproses</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- ============================================== -->
            <!-- 3. DOKTER DASHBOARD VIEW                       -->
            <!-- ============================================== -->
            @if(auth()->user()->isDokter())
                <div class="space-y-8">
                    <!-- Doctor Dashboard Split Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Left Panel: Queues to consult -->
                        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Antrean Pasien Anda Hari Ini</h3>
                                <p class="text-xs text-gray-500 mt-1">Menampilkan daftar pasien yang menunggu konsultasi/pemeriksaan Anda.</p>
                            </div>
                            <div class="p-6">
                                @if($todayQueues->isEmpty())
                                    <div class="text-center py-8 text-gray-500">Tidak ada pasien terdaftar untuk Anda hari ini.</div>
                                @else
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                            <thead class="bg-gray-50 dark:bg-gray-900/50 font-semibold text-gray-500">
                                                <tr>
                                                    <th class="px-6 py-3 text-left">No. Antrean</th>
                                                    <th class="px-6 py-3 text-left">Nama Pasien</th>
                                                    <th class="px-6 py-3 text-left">Sesi</th>
                                                    <th class="px-6 py-3 text-left">Status</th>
                                                    <th class="px-6 py-3 text-right">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                                @foreach($todayQueues as $q)
                                                    <tr>
                                                        <td class="px-6 py-4 font-bold">{{ $q->nomor_antrean }}</td>
                                                        <td class="px-6 py-4">{{ $q->patient->nama }}</td>
                                                        <td class="px-6 py-4">{{ $q->sesi }}</td>
                                                        <td class="px-6 py-4">
                                                            @php
                                                                $statusColors = [
                                                                    'Menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                                                    'Diperiksa' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                                                    'Selesai' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                                                    'Batal' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                                                ];
                                                                $color = $statusColors[$q->status] ?? 'bg-gray-100 text-gray-800';
                                                            @endphp
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $color }}">
                                                                {{ $q->status }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 text-right">
                                                            @if($q->status === 'Menunggu')
                                                                <a href="{{ route('medical-records.consult', $q->id) }}" class="inline-flex items-center px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded shadow-sm">
                                                                    Periksa Pasien
                                                                </a>
                                                            @elseif($q->status === 'Diperiksa')
                                                                <span class="text-xs text-blue-500 font-semibold italic">Menunggu Apotek</span>
                                                            @else
                                                                <a href="{{ route('patients.show', $q->patient_id) }}" class="text-xs text-indigo-500 hover:underline">Lihat Rekam Medis</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right Panel: Diagnose Chart (US-19) -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">Top 5 ICD-10 Diagnoses</h3>
                            <p class="text-xs text-gray-500 mb-6">Distribusi 5 diagnosis penyakit terbanyak yang telah Anda input.</p>
                            
                            @if($topDiagnoses->isEmpty())
                                <div class="flex-1 flex items-center justify-center text-center text-gray-400">
                                    Belum ada data rekam medis terdaftar untuk membuat diagram.
                                </div>
                            @else
                                <div class="flex-1 flex justify-center items-center">
                                    <div class="w-full max-w-[240px]">
                                        <canvas id="diagnosesChart"></canvas>
                                    </div>
                                </div>
                                <div class="mt-6 space-y-2 text-xs">
                                    @foreach($topDiagnoses as $diag)
                                        <div class="flex justify-between items-center text-gray-700 dark:text-gray-300">
                                            <span class="font-semibold">{{ $diag->kode_icd_10 }}</span>
                                            <span class="font-bold bg-indigo-50 dark:bg-indigo-950/30 px-2 py-0.5 rounded text-indigo-700 dark:text-indigo-400">{{ $diag->total }} Kasus</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Load Chart.js (US-19) -->
                @if(!$topDiagnoses->isEmpty())
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const ctx = document.getElementById('diagnosesChart').getContext('2d');
                            new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: {!! json_encode($topDiagnoses->pluck('kode_icd_10')) !!},
                                    datasets: [{
                                        data: {!! json_encode($topDiagnoses->pluck('total')) !!},
                                        backgroundColor: [
                                            '#3b82f6', // blue
                                            '#10b981', // green
                                            '#f59e0b', // amber
                                            '#ef4444', // red
                                            '#8b5cf6'  // violet
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
                        });
                    </script>
                @endif
            @endif

            <!-- ============================================== -->
            <!-- 4. PERAWAT DASHBOARD VIEW                      -->
            <!-- ============================================== -->
            @if(auth()->user()->isPerawat())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Pemeriksaan Tanda Vital Hari Ini (US-07)</h3>
                        <p class="text-xs text-gray-500 mt-1">Isi tanda-tanda vital pasien (Tekanan Darah, Nadi, Suhu, dll.) sebelum pasien melakukan konsultasi dengan Dokter.</p>
                    </div>
                    <div class="p-6">
                        @if($todayQueues->isEmpty())
                            <div class="text-center py-8 text-gray-500">Belum ada antrean yang terdaftar untuk hari ini.</div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                    <thead class="bg-gray-50 dark:bg-gray-900/50 font-semibold text-gray-500">
                                        <tr>
                                            <th class="px-6 py-3 text-left">No. Antrean</th>
                                            <th class="px-6 py-3 text-left">Nama Pasien</th>
                                            <th class="px-6 py-3 text-left">Dokter Tujuan</th>
                                            <th class="px-6 py-3 text-left">Status Antrean</th>
                                            <th class="px-6 py-3 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($todayQueues as $q)
                                            <tr>
                                                <td class="px-6 py-4 font-bold">{{ $q->nomor_antrean }}</td>
                                                <td class="px-6 py-4">{{ $q->patient->nama }}</td>
                                                <td class="px-6 py-4">{{ $q->doctor->name }}</td>
                                                <td class="px-6 py-4">
                                                    @php
                                                        $statusColors = [
                                                            'Menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                                            'Diperiksa' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                                            'Selesai' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                                            'Batal' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                                        ];
                                                        $color = $statusColors[$q->status] ?? 'bg-gray-100 text-gray-800';
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $color }}">
                                                        {{ $q->status }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    @if($q->status === 'Menunggu')
                                                        <a href="{{ route('medical-records.vitals', $q->id) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded transition">
                                                            Input Tanda Vital
                                                        </a>
                                                    @else
                                                        <span class="text-xs text-gray-400 italic">Sudah Diperiksa Dokter</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- ============================================== -->
            <!-- 5. APOTEKER DASHBOARD VIEW                     -->
            <!-- ============================================== -->
            @if(auth()->user()->isApoteker())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Antrean Resep Farmasi (US-13)</h3>
                        <p class="text-xs text-gray-500 mt-1">Daftar resep yang selesai diinput oleh dokter dan menunggu untuk diproses serta diserahkan ke pasien.</p>
                    </div>
                    <div class="p-6">
                        @if($pendingQueues->isEmpty())
                            <div class="text-center py-8 text-gray-500">Tidak ada resep obat tertunda hari ini.</div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                    <thead class="bg-gray-50 dark:bg-gray-900/50 font-semibold text-gray-500">
                                        <tr>
                                            <th class="px-6 py-3 text-left">No. Antrean</th>
                                            <th class="px-6 py-3 text-left">Nama Pasien</th>
                                            <th class="px-6 py-3 text-left">Dokter Pemeriksa</th>
                                            <th class="px-6 py-3 text-left">Tanggal</th>
                                            <th class="px-6 py-3 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($pendingQueues as $q)
                                            <tr>
                                                <td class="px-6 py-4 font-bold">{{ $q->nomor_antrean }}</td>
                                                <td class="px-6 py-4 font-semibold text-indigo-600 dark:text-indigo-400">{{ $q->patient->nama }}</td>
                                                <td class="px-6 py-4">{{ $q->doctor->name }}</td>
                                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($q->tanggal)->format('d-m-Y') }}</td>
                                                <td class="px-6 py-4 text-right">
                                                    <a href="{{ route('pharmacy.prescriptions') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded transition">
                                                        Buka Antrean Farmasi
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- ============================================== -->
            <!-- 6. PASIEN DASHBOARD VIEW                       -->
            <!-- ============================================== -->
            @if(auth()->user()->isPasien())
                @if(isset($no_profile) && $no_profile)
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-red-100 dark:border-red-950/20 text-center">
                        <svg class="h-12 w-12 text-red-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Profil Demografis Belum Terhubung!</h3>
                        <p class="text-sm text-gray-500 mt-2">Akun Anda belum terhubung dengan data rekam medis pasien di klinik. Silakan hubungi Resepsionis Klinik untuk menautkan akun ini.</p>
                    </div>
                @else
                    <div class="space-y-8">
                        <!-- Queue Alert Message (US-12) -->
                        @foreach($queueAlerts as $alert)
                            @if($alert['people_in_front'] <= 2)
                                <div class="p-5 bg-yellow-50 dark:bg-yellow-950/30 border-l-4 border-yellow-500 rounded-xl shadow-sm flex items-start gap-4">
                                    <div class="bg-yellow-100 dark:bg-yellow-900/30 p-2 rounded-lg text-yellow-600">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-base font-bold text-yellow-800 dark:text-yellow-400">Panggilan Antrean!</h4>
                                        <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                            Nomor antrean Anda <span class="font-extrabold">{{ $alert['queue']->nomor_antrean }}</span> tinggal 
                                            <span class="font-extrabold text-red-600 dark:text-red-400">
                                                {{ $alert['people_in_front'] == 0 ? 'giliran Anda sekarang!' : $alert['people_in_front'] . ' orang lagi di depan Anda!' }}
                                            </span> 
                                            Harap bersiap di dekat loket pemeriksaan.
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        <!-- Quick Portal Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Booking shortcut -->
                            <div class="bg-indigo-600 hover:bg-indigo-700 text-white p-6 rounded-xl shadow-md transition flex flex-col justify-between">
                                <div>
                                    <h3 class="text-xl font-bold">Ambil Antrean Online</h3>
                                    <p class="text-xs text-indigo-150 mt-2">Dapatkan nomor antrean berobat secara cepat tanpa perlu antre di klinik secara langsung.</p>
                                </div>
                                <a href="{{ route('queues.book') }}" class="mt-6 inline-flex items-center text-sm font-semibold hover:underline">Pesan Sekarang &rarr;</a>
                            </div>

                            <!-- My Queue status -->
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Antrean Hari Ini</h3>
                                    @if($myQueues->isEmpty())
                                        <p class="text-sm text-gray-400 mt-2">Anda tidak memiliki tiket antrean aktif untuk hari ini.</p>
                                    @else
                                        @foreach($myQueues as $q)
                                            <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-900 rounded-lg flex justify-between items-center">
                                                <div>
                                                    <span class="text-xs text-gray-400 font-semibold block">NOMOR ANTREAN</span>
                                                    <span class="text-xl font-extrabold text-gray-900 dark:text-gray-100">{{ $q->nomor_antrean }}</span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-xs text-gray-400 font-semibold block">STATUS</span>
                                                    <span class="text-sm font-bold text-indigo-500">{{ $q->status }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <!-- View Medical History -->
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Riwayat Rekam Medis</h3>
                                    <p class="text-sm text-gray-400 mt-2">Pantau riwayat diagnosis penyakit, catatan resep obat, dan riwayat kesehatan pribadi Anda secara transparan.</p>
                                </div>
                                <a href="{{ route('patients.show', $patient->id) }}" class="mt-6 text-sm text-indigo-500 hover:text-indigo-600 font-semibold inline-block">Lihat Riwayat &rarr;</a>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>
