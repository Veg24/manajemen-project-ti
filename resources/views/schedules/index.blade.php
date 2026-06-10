<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Kelola Jadwal Praktek Dokter') }}
            </h2>
            <a href="{{ route('doctor-schedules.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                + Tambah Jadwal Baru
            </a>
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
                    @if($schedules->isEmpty())
                        <div class="text-center py-8 text-gray-400">Belum ada jadwal praktek dokter terdaftar.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-900/50 font-semibold text-gray-500">
                                    <tr>
                                        <th class="px-6 py-3 text-left">Nama Dokter</th>
                                        <th class="px-6 py-3 text-left">Hari Praktek</th>
                                        <th class="px-6 py-3 text-left">Jam Mulai</th>
                                        <th class="px-6 py-3 text-left">Jam Selesai</th>
                                        <th class="px-6 py-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($schedules as $s)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-gray-100">{{ $s->doctor->name }}</td>
                                            <td class="px-6 py-4">{{ $s->hari }}</td>
                                            <td class="px-6 py-4 font-semibold text-green-600 dark:text-green-400">{{ \Carbon\Carbon::parse($s->jam_mulai)->format('H:i') }}</td>
                                            <td class="px-6 py-4 font-semibold text-red-650 dark:text-red-400">{{ \Carbon\Carbon::parse($s->jam_selesai)->format('H:i') }}</td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex justify-end gap-2">
                                                    <a href="{{ route('doctor-schedules.edit', $s->id) }}" class="text-xs text-yellow-600 font-bold hover:underline">Ubah</a>
                                                    
                                                    <form method="POST" action="{{ route('doctor-schedules.destroy', $s->id) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-xs text-red-500 font-bold hover:underline">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $schedules->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
