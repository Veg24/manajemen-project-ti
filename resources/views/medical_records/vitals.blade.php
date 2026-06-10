<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Input Tanda Vital Pasien') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <!-- Patient Info Card -->
            <div class="bg-indigo-50 dark:bg-indigo-950/20 p-5 rounded-xl border border-indigo-100 dark:border-indigo-900/30 mb-6 flex justify-between items-center text-sm">
                <div>
                    <h3 class="font-bold text-indigo-900 dark:text-indigo-300 text-base">{{ $queue->patient->nama }}</h3>
                    <span class="text-xs text-indigo-700 dark:text-indigo-400 block mt-0.5">NIK: {{ $queue->patient->NIK }}</span>
                    <span class="text-xs text-indigo-700 dark:text-indigo-400 block">Tgl Lahir: {{ \Carbon\Carbon::parse($queue->patient->tgl_lahir)->format('d-m-Y') }} ({{ \Carbon\Carbon::parse($queue->patient->tgl_lahir)->age }} Th)</span>
                </div>
                <div class="text-right">
                    <span class="text-xs text-gray-400 font-bold block">NOMOR ANTREAN</span>
                    <span class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $queue->nomor_antrean }}</span>
                </div>
            </div>

            <!-- Vitals Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('medical-records.store-vitals', $queue->id) }}" class="space-y-6">
                        @csrf

                        <!-- Tekanan Darah -->
                        <div>
                            <label for="tekanan_darah" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tekanan Darah (mmHg)</label>
                            <input type="text" name="tekanan_darah" id="tekanan_darah" value="{{ old('tekanan_darah', $record->tekanan_darah) }}" required placeholder="e.g. 120/80"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('tekanan_darah')" class="mt-2" />
                        </div>

                        <!-- Suhu -->
                        <div>
                            <label for="suhu" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Suhu Tubuh (°C)</label>
                            <input type="number" step="0.1" name="suhu" id="suhu" value="{{ old('suhu', $record->suhu) }}" required placeholder="e.g. 36.5"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('suhu')" class="mt-2" />
                        </div>

                        <!-- Nadi -->
                        <div>
                            <label for="nadi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Denyut Nadi (bpm)</label>
                            <input type="number" name="nadi" id="nadi" value="{{ old('nadi', $record->nadi) }}" required placeholder="e.g. 80"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('nadi')" class="mt-2" />
                        </div>

                        <!-- Berat Badan -->
                        <div>
                            <label for="berat_badan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Berat Badan (kg)</label>
                            <input type="number" step="0.1" name="berat_badan" id="berat_badan" value="{{ old('berat_badan', $record->berat_badan) }}" required placeholder="e.g. 65.2"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('berat_badan')" class="mt-2" />
                        </div>

                        <!-- Tinggi Badan -->
                        <div>
                            <label for="tinggi_badan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tinggi Badan (cm)</label>
                            <input type="number" step="0.1" name="tinggi_badan" id="tinggi_badan" value="{{ old('tinggi_badan', $record->tinggi_badan) }}" required placeholder="e.g. 170"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('tinggi_badan')" class="mt-2" />
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                Simpan Tanda Vital
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
