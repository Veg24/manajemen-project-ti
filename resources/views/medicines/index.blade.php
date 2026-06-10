<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Inventaris Obat Apotek') }}
            </h2>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('medicines.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    + Tambah Obat Baru
                </a>
            @endif
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

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-150 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    @if($medicines->isEmpty())
                        <div class="text-center py-8 text-gray-400">Belum ada data obat di inventaris.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-900/50 font-semibold text-gray-500">
                                    <tr>
                                        <th class="px-6 py-3 text-left">Nama Obat</th>
                                        <th class="px-6 py-3 text-left">Kategori</th>
                                        <th class="px-6 py-3 text-left">Harga Jual</th>
                                        <th class="px-6 py-3 text-left">Stok Saat Ini</th>
                                        <th class="px-6 py-3 text-left">Min. Stok</th>
                                        <th class="px-6 py-3 text-left">Status Stok</th>
                                        @if(auth()->user()->isAdmin())
                                            <th class="px-6 py-3 text-right">Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($medicines as $med)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-gray-100">{{ $med->nama }}</td>
                                            <td class="px-6 py-4">{{ $med->kategori }}</td>
                                            <td class="px-6 py-4 font-semibold">Rp{{ number_format($med->harga, 2, ',', '.') }}</td>
                                            <td class="px-6 py-4 font-bold">{{ $med->stok }} {{ $med->satuan }}</td>
                                            <td class="px-6 py-4 text-gray-500">{{ $med->min_stock }} {{ $med->satuan }}</td>
                                            <td class="px-6 py-4">
                                                <!-- Low Stock Warning (US-15) -->
                                                @if($med->stok <= $med->min_stock)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-red-150 text-red-800 dark:bg-red-950/40 dark:text-red-300 animate-pulse border border-red-200">
                                                        Stok Kritis!
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                        Aman
                                                    </span>
                                                @endif
                                            </td>
                                            @if(auth()->user()->isAdmin())
                                                <td class="px-6 py-4 text-right">
                                                    <div class="flex justify-end gap-2">
                                                        <a href="{{ route('medicines.edit', $med->id) }}" class="text-xs text-yellow-600 font-bold hover:underline">Ubah</a>
                                                        
                                                        <form method="POST" action="{{ route('medicines.destroy', $med->id) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus obat ini dari inventaris?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-xs text-red-500 font-bold hover:underline">Hapus</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $medicines->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
