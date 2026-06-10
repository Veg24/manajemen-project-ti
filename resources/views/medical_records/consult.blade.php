<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Konsultasi Medis Pasien') }}
            </h2>
            <div class="text-right">
                <span class="text-xs text-gray-400 font-bold block">NOMOR ANTREAN</span>
                <span class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $queue->nomor_antrean }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- LEFT COLUMN: Consultation & Prescription Form (2/3 width) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Patient Basic Info Card -->
                    <div class="bg-indigo-50 dark:bg-indigo-950/20 p-5 rounded-xl border border-indigo-100 dark:border-indigo-900/30">
                        <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-300">{{ $queue->patient->nama }}</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-3 text-xs text-indigo-800 dark:text-indigo-400">
                            <div><span class="font-semibold">NIK:</span> {{ $queue->patient->NIK }}</div>
                            <div><span class="font-semibold">Tgl Lahir:</span> {{ \Carbon\Carbon::parse($queue->patient->tgl_lahir)->format('d-m-Y') }} ({{ \Carbon\Carbon::parse($queue->patient->tgl_lahir)->age }} Th)</div>
                            <div><span class="font-semibold">No. Telp:</span> {{ $queue->patient->no_telp }}</div>
                        </div>
                    </div>

                    <!-- Consultation Form -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700 overflow-hidden">
                        <div class="p-6">
                            <form method="POST" action="{{ route('medical-records.store-consult', $queue->id) }}" class="space-y-6">
                                @csrf

                                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 border-b border-gray-100 dark:border-gray-700 pb-2">1. Validasi Tanda Vital</h3>
                                
                                <!-- Vital Signs Grid -->
                                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                    <div>
                                        <label for="tekanan_darah" class="block text-xs font-semibold text-gray-500">TD (mmHg)</label>
                                        <input type="text" name="tekanan_darah" id="tekanan_darah" value="{{ old('tekanan_darah', $record->tekanan_darah) }}" required
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:ring-indigo-500 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="suhu" class="block text-xs font-semibold text-gray-500">Suhu (°C)</label>
                                        <input type="number" step="0.1" name="suhu" id="suhu" value="{{ old('suhu', $record->suhu) }}" required
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:ring-indigo-500 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="nadi" class="block text-xs font-semibold text-gray-500">Nadi (bpm)</label>
                                        <input type="number" name="nadi" id="nadi" value="{{ old('nadi', $record->nadi) }}" required
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:ring-indigo-500 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="berat_badan" class="block text-xs font-semibold text-gray-500">BB (kg)</label>
                                        <input type="number" step="0.1" name="berat_badan" id="berat_badan" value="{{ old('berat_badan', $record->berat_badan) }}" required
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:ring-indigo-500 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="tinggi_badan" class="block text-xs font-semibold text-gray-500">TB (cm)</label>
                                        <input type="number" step="0.1" name="tinggi_badan" id="tinggi_badan" value="{{ old('tinggi_badan', $record->tinggi_badan) }}" required
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:ring-indigo-500 shadow-sm">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400">Tanda vital di atas divalidasi ulang oleh dokter dari isian perawat.</p>

                                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 border-b border-gray-100 dark:border-gray-700 pb-2 pt-4">2. Diagnosis Medis</h3>
                                
                                <!-- Keluhan -->
                                <div>
                                    <label for="keluhan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keluhan Pasien</label>
                                    <textarea name="keluhan" id="keluhan" rows="3" required
                                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-indigo-500 shadow-sm">{{ old('keluhan', $record->keluhan) }}</textarea>
                                </div>

                                <!-- ICD-10 Code & Medical Action -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="md:col-span-1">
                                        <label for="kode_icd_10" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode ICD-10</label>
                                        <input type="text" name="kode_icd_10" id="kode_icd_10" value="{{ old('kode_icd_10', $record->kode_icd_10) }}" required placeholder="e.g. A09"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-indigo-500 shadow-sm">
                                        <x-input-error :messages="$errors->get('kode_icd_10')" class="mt-2" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="tindakan_medis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tindakan Medis</label>
                                        <input type="text" name="tindakan_medis" id="tindakan_medis" value="{{ old('tindakan_medis', $record->tindakan_medis) }}" required placeholder="e.g. Pemberian kompres, edukasi hidrasi"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-indigo-500 shadow-sm">
                                        <x-input-error :messages="$errors->get('tindakan_medis')" class="mt-2" />
                                    </div>
                                </div>

                                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 border-b border-gray-100 dark:border-gray-700 pb-2 pt-4 flex justify-between items-center">
                                    <span>3. Resep Digital Obat (US-06)</span>
                                    <button type="button" onclick="addPrescriptionRow()" class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded shadow-sm">
                                        + Tambah Obat
                                    </button>
                                </h3>

                                <!-- Dynamic Prescription Rows -->
                                <div id="prescription-container" class="space-y-4">
                                    <!-- Row template appended here via Javascript -->
                                </div>

                                <!-- Form Action Buttons -->
                                <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        Batal
                                    </a>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                        Simpan & Selesaikan Konsultasi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Chronological Medical History Timeline (1/3 width) (US-08) -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700 p-6 space-y-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 border-b border-gray-100 dark:border-gray-700 pb-2">
                            Riwayat Medis Terdahulu
                        </h3>

                        @if($history->isEmpty())
                            <p class="text-sm text-gray-400 italic text-center py-6">Pasien belum memiliki riwayat pemeriksaan sebelumnya.</p>
                        @else
                            <div class="space-y-4 overflow-y-auto max-h-[600px] pr-2">
                                @foreach($history as $item)
                                    <div class="p-4 bg-gray-50 dark:bg-gray-900/40 rounded-lg border border-gray-200 dark:border-gray-750 text-xs space-y-2">
                                        <div class="flex justify-between font-bold text-indigo-600">
                                            <span>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</span>
                                            <span class="bg-indigo-50 dark:bg-indigo-950/30 px-1.5 py-0.5 rounded text-[10px]">ICD-10: {{ $item->kode_icd_10 }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 block font-semibold uppercase text-[9px]">Keluhan</span>
                                            <span class="text-gray-900 dark:text-gray-100">{{ $item->keluhan }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 block font-semibold uppercase text-[9px]">Tindakan</span>
                                            <span class="text-gray-900 dark:text-gray-100">{{ $item->tindakan_medis }}</span>
                                        </div>
                                        @if($item->medicines->isNotEmpty())
                                            <div class="mt-2 bg-indigo-50/30 dark:bg-indigo-950/10 p-2 rounded">
                                                <span class="text-[9px] font-bold text-indigo-700 block mb-1">Resep Obat</span>
                                                <ul class="space-y-0.5 list-disc pl-3 text-[10px]">
                                                    @foreach($item->medicines as $med)
                                                        <li>{{ $med->nama }} - <span class="italic font-medium">{{ $med->pivot->dosis }}</span></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Javascript for Dynamic Prescriptions (US-06) -->
    <script>
        let rowIdx = 0;
        const medicines = {!! json_encode($medicines) !!};

        function addPrescriptionRow() {
            const container = document.getElementById('prescription-container');
            
            // Create options HTML
            let optionsHtml = '<option value="">-- Pilih Obat --</option>';
            medicines.forEach(med => {
                optionsHtml += `<option value="${med.id}">${med.nama} (Stok: ${med.stok} ${med.satuan})</option>`;
            });

            const row = document.createElement('div');
            row.id = `presc-row-${rowIdx}`;
            row.className = 'flex flex-col md:flex-row gap-3 items-end bg-gray-50 dark:bg-gray-900/60 p-4 rounded-lg border border-gray-150 dark:border-gray-750 relative';
            row.innerHTML = `
                <div class="flex-1 w-full">
                    <label class="block text-xs font-semibold text-gray-500">Nama Obat</label>
                    <select name="prescriptions[${rowIdx}][medicine_id]" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:ring-indigo-500 shadow-sm">
                        ${optionsHtml}
                    </select>
                </div>
                <div class="flex-1 w-full">
                    <label class="block text-xs font-semibold text-gray-500">Dosis / Aturan Pakai</label>
                    <input type="text" name="prescriptions[${rowIdx}][dosis]" required placeholder="e.g. 3x1 tablet setelah makan"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:ring-indigo-500 shadow-sm" />
                </div>
                <div class="w-full md:w-28">
                    <label class="block text-xs font-semibold text-gray-500">Jumlah Obat</label>
                    <input type="number" name="prescriptions[${rowIdx}][jumlah]" required min="1" placeholder="10"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:ring-indigo-500 shadow-sm" />
                </div>
                <button type="button" onclick="removePrescriptionRow(${rowIdx})" class="text-sm font-bold text-red-500 hover:text-red-700 pb-2 flex items-center justify-center self-center md:self-end">
                    <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Hapus
                </button>
            `;
            
            container.appendChild(row);
            rowIdx++;
        }

        function removePrescriptionRow(idx) {
            const row = document.getElementById(`presc-row-${idx}`);
            if (row) {
                row.remove();
            }
        }

        // Initialize with one empty row for doctor convenience
        document.addEventListener("DOMContentLoaded", function() {
            addPrescriptionRow();
        });
    </script>
</x-app-layout>
