<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Jadwal Praktek Dokter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700 overflow-hidden">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('doctor-schedules.store') }}" class="space-y-6">
                        @csrf

                        <!-- Doctor -->
                        <div>
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Dokter</label>
                            <select name="doctor_id" id="doctor_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <option value="">-- Pilih Dokter --</option>
                                @foreach($doctors as $doc)
                                    <option value="{{ $doc->id }}" {{ old('doctor_id') == $doc->id ? 'selected' : '' }}>
                                        {{ $doc->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('doctor_id')" class="mt-2" />
                        </div>

                        <!-- Hari -->
                        <div>
                            <label for="hari" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hari Praktek</label>
                            <select name="hari" id="hari" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <option value="">-- Pilih Hari --</option>
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                                    <option value="{{ $day }}" {{ old('hari') == $day ? 'selected' : '' }}>{{ $day }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('hari')" class="mt-2" />
                        </div>

                        <!-- Jam Mulai & Selesai -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="jam_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Mulai</label>
                                <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai') }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <x-input-error :messages="$errors->get('jam_mulai')" class="mt-2" />
                            </div>
                            <div>
                                <label for="jam_selesai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Selesai</label>
                                <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai') }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <x-input-error :messages="$errors->get('jam_selesai')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end gap-3 border-t border-gray-100 dark:border-gray-700 pt-4">
                            <a href="{{ route('doctor-schedules.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
