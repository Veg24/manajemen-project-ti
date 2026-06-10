<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Antrean Resep Farmasi Apotek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Alert success / error -->
            @if(session('success'))
                <div class="p-4 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 text-green-800 dark:text-green-300 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-800 dark:text-red-300 rounded shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    @if(empty($prescriptions))
                        <div class="text-center py-12 text-gray-400">Tidak ada resep tertunda yang perlu diproses saat ini.</div>
                    @else
                        <div class="space-y-8">
                            @foreach($prescriptions as $item)
                                <div class="bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-750 p-6 rounded-xl space-y-4">
                                    
                                    <!-- Prescription Title / Header -->
                                    <div class="flex flex-col md:flex-row md:justify-between md:items-center border-b border-gray-250 dark:border-gray-700 pb-3 gap-2">
                                        <div>
                                            <span class="text-xs text-gray-400 font-bold block">NOMOR ANTREAN</span>
                                            <span class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $item['queue']->nomor_antrean }}</span>
                                        </div>
                                        <div>
                                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100">Pasien: {{ $item['queue']->patient->nama }}</span>
                                            <div class="text-xs text-gray-500 mt-0.5">Dokter Pemeriksa: {{ $item['queue']->doctor->name }}</div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs text-gray-400 font-bold block">TANGGAL PEMERIKSAAN</span>
                                            <span class="text-sm font-semibold">{{ \Carbon\Carbon::parse($item['record']->created_at)->format('d-m-Y - H:i') }}</span>
                                        </div>
                                    </div>

                                    <!-- Prescription Details (US-13) -->
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                            <svg class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                            </svg>
                                            Rincian Resep & Dosis Obat
                                        </h4>
                                        
                                        @php $hasInsufficientStock = false; @endphp
                                        <div class="overflow-x-auto bg-white dark:bg-gray-850 rounded-lg border border-gray-150 dark:border-gray-700">
                                            <table class="min-w-full text-xs text-left">
                                                <thead class="bg-gray-100 dark:bg-gray-900/60 font-semibold text-gray-600 dark:text-gray-400">
                                                    <tr>
                                                        <th class="px-4 py-2.5">Nama Obat</th>
                                                        <th class="px-4 py-2.5">Dosis / Instruksi Dokter</th>
                                                        <th class="px-4 py-2.5">Jumlah Diminta</th>
                                                        <th class="px-4 py-2.5">Stok Apotek Saat Ini</th>
                                                        <th class="px-4 py-2.5">Status Persediaan</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                                    @foreach($item['record']->medicines as $med)
                                                        @php
                                                            $insufficient = $med->stok < $med->pivot->jumlah;
                                                            if($insufficient) { $hasInsufficientStock = true; }
                                                        @endphp
                                                        <tr class="{{ $insufficient ? 'bg-red-50/50 dark:bg-red-950/10' : '' }}">
                                                            <td class="px-4 py-3 font-semibold text-gray-800 dark:text-gray-200">{{ $med->nama }}</td>
                                                            <td class="px-4 py-3 italic font-medium text-gray-700 dark:text-gray-300">{{ $med->pivot->dosis }}</td>
                                                            <td class="px-4 py-3 font-bold text-gray-900 dark:text-gray-100">{{ $med->pivot->jumlah }} {{ $med->satuan }}</td>
                                                            <td class="px-4 py-3 font-semibold">{{ $med->stok }} {{ $med->satuan }}</td>
                                                            <td class="px-4 py-3">
                                                                @if($insufficient)
                                                                    <span class="text-red-600 dark:text-red-400 font-bold flex items-center">
                                                                        <svg class="h-4 w-4 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                                        Stok Kurang!
                                                                    </span>
                                                                @else
                                                                    <span class="text-green-600 dark:text-green-400 font-semibold flex items-center">
                                                                        <svg class="h-4 w-4 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                                        Cukup
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Actions Panel (US-14) -->
                                    <div class="flex justify-between items-center bg-white dark:bg-gray-850 p-4 rounded-lg border border-gray-150 dark:border-gray-700">
                                        <div>
                                            @if($hasInsufficientStock)
                                                <span class="text-xs text-red-600 font-bold block">* Tidak dapat memproses resep karena ada stok obat yang kurang.</span>
                                                <span class="text-[10px] text-gray-400 block">Silakan restock obat terlebih dahulu di menu inventaris.</span>
                                            @else
                                                <span class="text-xs text-green-600 font-bold block">&check; Semua stok mencukupi untuk diserahkan.</span>
                                            @endif
                                        </div>
                                        
                                        <form method="POST" action="{{ route('pharmacy.dispense', $item['record']->id) }}">
                                            @csrf
                                            <button type="submit" 
                                                    {{ $hasInsufficientStock ? 'disabled' : '' }}
                                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase tracking-wider rounded transition {{ $hasInsufficientStock ? 'opacity-30 cursor-not-allowed bg-gray-400 hover:bg-gray-400' : '' }}">
                                                Serahkan Obat & Selesai
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
