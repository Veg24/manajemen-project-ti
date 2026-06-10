<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ubah Data Pasien') }}: {{ $patient->nama }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('patients.update', $patient->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- NIK -->
                        <div>
                            <label for="NIK" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIK (16 Digit)</label>
                            <input type="text" name="NIK" id="NIK" value="{{ old('NIK', $patient->NIK) }}" minlength="16" maxlength="16" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('NIK')" class="mt-2" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Nomor Induk Kependudukan harus tepat 16 digit angka.</p>
                        </div>

                        <!-- Nama -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap Pasien</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $patient->nama) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="tgl_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" id="tgl_lahir" value="{{ old('tgl_lahir', $patient->tgl_lahir) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('tgl_lahir')" class="mt-2" />
                        </div>

                        <!-- No Telp -->
                        <div>
                            <label for="no_telp" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. Telepon / WhatsApp</label>
                            <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp', $patient->no_telp) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('no_telp')" class="mt-2" />
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamat" rows="3" required
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">{{ old('alamat', $patient->alamat) }}</textarea>
                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                        </div>

                        <!-- Form Action Buttons -->
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
