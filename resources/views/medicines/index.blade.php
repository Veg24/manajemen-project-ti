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
                                                    <div class="flex justify-end items-center gap-2">
                                                        <a href="{{ route('medicines.edit', $med->id) }}" 
                                                           class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-md border border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-900/50 dark:bg-amber-950/20 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-950/40 transition duration-150 shadow-xs">
                                                            <svg class="w-3.5 h-3.5 mr-1 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                            Ubah
                                                        </a>
                                                        
                                                        <form method="POST" action="{{ route('medicines.destroy', $med->id) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus obat ini dari inventaris?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-md border border-red-200 bg-red-50 text-red-700 dark:border-red-900/50 dark:bg-red-950/20 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-950/40 transition duration-150 shadow-xs cursor-pointer">
                                                                <svg class="w-3.5 h-3.5 mr-1 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                                Hapus
                                                            </button>
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
