<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manajemen Pasien') }} {{ request('status') === 'nonaktif' ? '(Nonaktif)' : '(Aktif)' }}
            </h2>
            @if(auth()->user()->isResepsionis() || auth()->user()->isAdmin())
                <a href="{{ route('patients.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    + Daftar Pasien Baru
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Success -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 text-green-800 dark:text-green-300 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search and Filter Bar -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <form method="GET" action="{{ route('patients.index') }}" class="flex-1 flex gap-2">
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan Nama atau NIK..." class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('patients.index', ['status' => request('status')]) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none transition ease-in-out duration-150">
                                Reset
                            </a>
                        @endif
                    </form>

                    <!-- Filter Status (Admin Only) -->
                    @if(auth()->user()->isAdmin())
                        <div class="flex gap-2">
                            <a href="{{ route('patients.index') }}" class="px-4 py-2 text-sm font-medium rounded-md {{ request('status') !== 'nonaktif' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                Data Aktif
                            </a>
                            <a href="{{ route('patients.index', ['status' => 'nonaktif']) }}" class="px-4 py-2 text-sm font-medium rounded-md {{ request('status') === 'nonaktif' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                Data Nonaktif
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Table of Patients -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($patients->isEmpty())
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            {{ __('Data pasien tidak ditemukan.') }}
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900/50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Pasien</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">NIK</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal Lahir</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">No. Telepon</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alamat</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($patients as $patient)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $patient->nama }}</div>
                                                @if($patient->user)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                        Punya Akun
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $patient->NIK }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($patient->tgl_lahir)->format('d-m-Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $patient->no_telp }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                                {{ $patient->alamat }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex justify-end items-center gap-2">
                                                    <!-- Detail Profile (US-03) -->
                                                    <a href="{{ route('patients.show', $patient->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Detail</a>
                                                    
                                                    @if(request('status') !== 'nonaktif')
                                                        @if(auth()->user()->isResepsionis() || auth()->user()->isAdmin())
                                                            <a href="{{ route('patients.edit', $patient->id) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">Edit</a>
                                                        @endif
                                                        
                                                        @if(auth()->user()->isAdmin())
                                                            <form method="POST" action="{{ route('patients.destroy', $patient->id) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan pasien ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                                    Deaktif
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @else
                                                        @if(auth()->user()->isAdmin())
                                                            <!-- Restore for Admin (US-04) -->
                                                            <form method="POST" action="{{ route('patients.restore', $patient->id) }}" class="inline-block">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 font-semibold">
                                                                    Aktifkan Kembali
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Links -->
                        <div class="mt-6">
                            {{ $patients->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
