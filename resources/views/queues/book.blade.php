<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pemesanan Nomor Antrean Online') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- LEFT PANEL: Booking Form (2/3 width) -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700 p-6">
                    <form method="POST" action="{{ route('queues.store-book') }}" class="space-y-6">
                        @csrf

                        <!-- Patient Selection -->
                        @if(auth()->user()->isPasien())
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Nama Pasien</label>
                                <span class="text-base font-bold text-gray-900 dark:text-gray-100 block mt-1">{{ $patient->nama }}</span>
                                <span class="text-xs text-gray-500 block">NIK: {{ $patient->NIK }}</span>
                            </div>
                        @else
                            <!-- Admin/Receptionist selecting patient -->
                            <div>
                                <label for="patient_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Pasien</label>
                                <select name="patient_id" id="patient_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                    <option value="">-- Pilih Pasien --</option>
                                    @foreach($patients as $p)
                                        <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama }} (NIK: {{ $p->NIK }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                            </div>
                        @endif

                        <!-- Doctor Selection -->
                        <div>
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Dokter / Poli</label>
                            <select name="doctor_id" id="doctor_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <option value="">-- Pilih Dokter / Poli --</option>
                                @foreach($doctors as $doc)
                                    <option value="{{ $doc->id }}" {{ old('doctor_id') == $doc->id ? 'selected' : '' }}>
                                        {{ $doc->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('doctor_id')" class="mt-2" />
                        </div>

                        <!-- Date Selection -->
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Kunjungan</label>
                            <input type="date" name="tanggal" id="tanggal" min="{{ date('y-m-d') }}" value="{{ old('tanggal', date('Y-m-d')) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                        </div>

                        <!-- Session Selection -->
                        <div>
                            <label for="sesi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sesi Waktu Kunjungan</label>
                            <select name="sesi" id="sesi" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <option value="">-- Pilih Sesi --</option>
                                <option value="Pagi" {{ old('sesi') == 'Pagi' ? 'selected' : '' }}>Pagi (08:00 - 12:00)</option>
                                <option value="Siang" {{ old('sesi') == 'Siang' ? 'selected' : '' }}>Siang (13:00 - 15:00)</option>
                                <option value="Sore" {{ old('sesi') == 'Sore' ? 'selected' : '' }}>Sore (15:30 - 17:00)</option>
                            </select>
                            <x-input-error :messages="$errors->get('sesi')" class="mt-2" />
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                Pesan Antrean
                            </button>
                        </div>
                    </form>
                </div>

                <!-- RIGHT PANEL: Doctor Schedule Reference (1/3 width) -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-indigo-50 dark:bg-indigo-950/20 p-6 rounded-xl border border-indigo-100 dark:border-indigo-900/30">
                        <h3 class="text-sm font-bold text-indigo-900 dark:text-indigo-300 border-b border-indigo-200 dark:border-indigo-900/50 pb-2 mb-4">
                            Panduan Jadwal Praktek Dokter
                        </h3>
                        <div class="space-y-4 text-xs text-indigo-855 dark:text-indigo-300">
                            @foreach($doctors as $doc)
                                <div>
                                    <span class="font-bold text-sm text-indigo-950 dark:text-indigo-200 block">{{ $doc->name }}</span>
                                    @if($doc->schedules->isEmpty())
                                        <span class="text-gray-400 italic block mt-1">Belum ada jadwal praktek.</span>
                                    @else
                                        <ul class="mt-1.5 space-y-1">
                                            @foreach($doc->schedules as $sched)
                                                <li class="flex justify-between">
                                                    <span>{{ $sched->hari }}</span>
                                                    <span class="font-semibold">{{ \Carbon\Carbon::parse($sched->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sched->jam_selesai)->format('H:i') }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900/30 p-6 rounded-xl border border-gray-200 dark:border-gray-750 text-xs text-gray-500 space-y-2">
                        <h4 class="font-bold text-gray-750 dark:text-gray-300">Ketentuan Antrean Online</h4>
                        <p>1. Pemesanan antrean online dapat dilakukan maksimal H-7 hingga hari kunjungan sebelum jam operasional dimulai.</p>
                        <p>2. Kapasitas antrean dibatasi maksimal 10 pasien per dokter untuk setiap sesi praktek.</p>
                        <p>3. Jika dokter tidak memiliki jadwal praktek pada tanggal yang dipilih, pemesanan akan otomatis diblokir.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
