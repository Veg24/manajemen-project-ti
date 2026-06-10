<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ubah Data Obat') }}: {{ $medicine->nama }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700 overflow-hidden">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('medicines.update', $medicine->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Nama Obat -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Obat</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $medicine->nama) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>

                        <!-- Kategori Obat -->
                        <div>
                            <label for="kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                            <input type="text" name="kategori" id="kategori" value="{{ old('kategori', $medicine->kategori) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('kategori')" class="mt-2" />
                        </div>

                        <!-- Satuan Obat -->
                        <div>
                            <label for="satuan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Satuan</label>
                            <input type="text" name="satuan" id="satuan" value="{{ old('satuan', $medicine->satuan) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('satuan')" class="mt-2" />
                        </div>

                        <!-- Harga Jual -->
                        <div>
                            <label for="harga" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Jual (Rupiah)</label>
                            <input type="number" step="0.01" min="0" name="harga" id="harga" value="{{ old('harga', $medicine->harga) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <x-input-error :messages="$errors->get('harga')" class="mt-2" />
                        </div>

                        <!-- Stok & Min Stok -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="stok" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stok Saat Ini</label>
                                <input type="number" min="0" name="stok" id="stok" value="{{ old('stok', $medicine->stok) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <x-input-error :messages="$errors->get('stok')" class="mt-2" />
                            </div>
                            <div>
                                <label for="min_stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Batas Min. Stok</label>
                                <input type="number" min="0" name="min_stock" id="min_stock" value="{{ old('min_stock', $medicine->min_stock) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <x-input-error :messages="$errors->get('min_stock')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end gap-3 border-t border-gray-100 dark:border-gray-700 pt-4">
                            <a href="{{ route('medicines.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
