<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Pasien Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('patients.store') }}" class="space-y-6">
                        @csrf

                        <!-- NIK -->
                        <div>
                            <label for="NIK" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIK (16 Digit)</label>
                            <input type="text" name="NIK" id="NIK" value="{{ old('NIK') }}" minlength="16" maxlength="16" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('NIK')" class="mt-2" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Nomor Induk Kependudukan harus tepat 16 digit angka.</p>
                        </div>

                        <!-- Nama -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap Pasien</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="tgl_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" id="tgl_lahir" value="{{ old('tgl_lahir') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('tgl_lahir')" class="mt-2" />
                        </div>

                        <!-- No Telp -->
                        <div>
                            <label for="no_telp" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. Telepon / WhatsApp</label>
                            <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('no_telp')" class="mt-2" />
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamat" rows="3" required
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">{{ old('alamat') }}</textarea>
                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                        </div>

                        <hr class="border-gray-200 dark:border-gray-700 my-6" />

                        <!-- Create Account Toggle -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="create_account" name="create_account" type="checkbox" value="1" {{ old('create_account') ? 'checked' : '' }}
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded dark:bg-gray-900 dark:border-gray-700"
                                       onchange="toggleAccountFields(this)">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="create_account" class="font-medium text-gray-700 dark:text-gray-300">Buat Akun Portal Online Pasien?</label>
                                <p class="text-gray-500 dark:text-gray-400">Centang ini jika pasien ingin masuk ke portal online untuk memesan nomor antrean secara mandiri.</p>
                            </div>
                        </div>

                        <!-- Account Credentials Fields (Hidden by default) -->
                        <div id="account_fields" class="{{ old('create_account') ? '' : 'hidden' }} space-y-6 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border border-gray-150 dark:border-gray-750">
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Informasi Login</h3>
                            
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                                    <input type="password" name="password" id="password"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Form Action Buttons -->
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan Pasien
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Toggle Javascript -->
    <script>
        function toggleAccountFields(checkbox) {
            const fieldsDiv = document.getElementById('account_fields');
            const emailInput = document.getElementById('email');
            const passInput = document.getElementById('password');
            const passConfirmInput = document.getElementById('password_confirmation');
            
            if (checkbox.checked) {
                fieldsDiv.classList.remove('hidden');
                emailInput.setAttribute('required', 'required');
                passInput.setAttribute('required', 'required');
            } else {
                fieldsDiv.classList.add('hidden');
                emailInput.removeAttribute('required');
                passInput.removeAttribute('required');
                
                // Clear fields on hide
                emailInput.value = '';
                passInput.value = '';
                passConfirmInput.value = '';
            }
        }
    </script>
</x-app-layout>
