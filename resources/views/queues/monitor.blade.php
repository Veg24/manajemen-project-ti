<x-app-layout>
    <head>
        <meta http-equiv="refresh" content="10">
    </head>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Monitor Antrean Klinik Hari Ini') }}
            </h2>
            <div class="flex gap-2">
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-gray-100 dark:bg-gray-800 text-gray-500">
                    Auto-refreshing every 10s...
                </span>
                @if(auth()->user()->isResepsionis() || auth()->user()->isAdmin())
                    <a href="{{ route('queues.book') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                        + Tambah Antrean Pasien
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert success -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 text-green-800 dark:text-green-300 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Aggregate counters -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Antrean</span>
                    <span class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 block mt-1">{{ $queues->count() }}</span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider text-yellow-600">Menunggu</span>
                    <span class="text-3xl font-extrabold text-yellow-650 block mt-1">{{ $queues->where('status', 'Menunggu')->count() }}</span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider text-blue-600">Diperiksa</span>
                    <span class="text-3xl font-extrabold text-blue-650 block mt-1">{{ $queues->where('status', 'Diperiksa')->count() }}</span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider text-green-600">Selesai</span>
                    <span class="text-3xl font-extrabold text-green-650 block mt-1">{{ $queues->where('status', 'Selesai')->count() }}</span>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    @if($queues->isEmpty())
                        <div class="text-center py-12 text-gray-400">Belum ada antrean berobat terdaftar untuk hari ini.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-900/50 font-semibold text-gray-500">
                                    <tr>
                                        <th class="px-6 py-3 text-left">No. Antrean</th>
                                        <th class="px-6 py-3 text-left">Nama Pasien</th>
                                        <th class="px-6 py-3 text-left">NIK Pasien</th>
                                        <th class="px-6 py-3 text-left">Dokter Tujuan</th>
                                        <th class="px-6 py-3 text-left">Sesi Praktek</th>
                                        <th class="px-6 py-3 text-left">Status</th>
                                        <th class="px-6 py-3 text-right">Aksi Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($queues as $q)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/10 transition">
                                            <td class="px-6 py-4 font-extrabold text-base text-indigo-600 dark:text-indigo-400">{{ $q->nomor_antrean }}</td>
                                            <td class="px-6 py-4 font-semibold">{{ $q->patient->nama }}</td>
                                            <td class="px-6 py-4 text-gray-500">{{ $q->patient->NIK }}</td>
                                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $q->doctor->name }}</td>
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
                                                <div class="flex justify-end gap-2">
                                                    @if($q->status === 'Menunggu')
                                                        <!-- Option to cancel -->
                                                        <form method="POST" action="{{ route('queues.update-status', $q->id) }}" class="inline-block">
                                                            @csrf
                                                            <input type="hidden" name="status" value="Batal">
                                                            <button type="submit" class="inline-flex items-center px-2.5 py-1 bg-red-100 hover:bg-red-200 text-red-800 text-xs font-bold rounded" onclick="return confirm('Apakah Anda yakin ingin membatalkan antrean ini?')">
                                                                Batalkan
                                                            </button>
                                                        </form>
                                                    @elseif($q->status === 'Diperiksa')
                                                        <span class="text-xs text-blue-500 font-semibold italic">Dalam Pelayanan Apotek</span>
                                                    @elseif($q->status === 'Selesai')
                                                        <span class="text-xs text-green-600 font-semibold">Terselesaikan</span>
                                                    @else
                                                        <span class="text-xs text-red-500 font-semibold">Dibatalkan</span>
                                                    @endif
                                                </div>
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
    </div>
</x-app-layout>
