<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Profil Pasien') }}: {{ $patient->nama }}
            </h2>
            <div class="flex gap-2">
                @if(auth()->user()->isResepsionis() || auth()->user()->isAdmin())
                    <a href="{{ route('patients.edit', $patient->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-750 transition ease-in-out duration-150">
                        Ubah Profil
                    </a>
                @endif
                <a href="{{ auth()->user()->isPasien() ? route('dashboard') : route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 transition ease-in-out duration-150">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Patient Personal Information Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Informasi Demografis Pasien
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div>
                                <span class="text-xs text-gray-400 uppercase tracking-wider block font-semibold">Nama Lengkap</span>
                                <span class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $patient->nama }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 uppercase tracking-wider block font-semibold">Nomor Induk Kependudukan (NIK)</span>
                                <span class="text-base text-gray-900 dark:text-gray-100">{{ $patient->NIK }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 uppercase tracking-wider block font-semibold">Tanggal Lahir / Usia</span>
                                <span class="text-base text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($patient->tgl_lahir)->format('d F Y') }}
                                    <span class="text-gray-400 font-normal">({{ \Carbon\Carbon::parse($patient->tgl_lahir)->age }} Tahun)</span>
                                </span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <span class="text-xs text-gray-400 uppercase tracking-wider block font-semibold">No. Telepon / WhatsApp</span>
                                <span class="text-base text-gray-900 dark:text-gray-100">{{ $patient->no_telp }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 uppercase tracking-wider block font-semibold">Alamat Lengkap</span>
                                <span class="text-base text-gray-900 dark:text-gray-100">{{ $patient->alamat }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 uppercase tracking-wider block font-semibold">Akun Terhubung</span>
                                <span class="text-base text-gray-900 dark:text-gray-100">
                                    @if($patient->user)
                                        <span class="text-green-600 dark:text-green-400 font-semibold">{{ $patient->user->email }}</span>
                                    @else
                                        <span class="text-gray-400 italic">Belum membuat akun portal online</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medical History Timeline (US-03 & US-08) -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Riwayat Kunjungan Medis (Kronologis Descending)
                    </h3>

                    @if($medicalRecords->isEmpty())
                        <div class="text-center py-8 bg-gray-50 dark:bg-gray-900/30 rounded border border-dashed border-gray-200 dark:border-gray-700">
                            <p class="text-gray-500 dark:text-gray-400">Belum ada riwayat kunjungan medis untuk pasien ini.</p>
                        </div>
                    @else
                        <div class="space-y-6 relative before:absolute before:inset-0 before:left-4 before:md:left-6 before:w-0.5 before:bg-gray-200 dark:before:bg-gray-700">
                            @foreach($medicalRecords as $record)
                                <div class="relative pl-10 md:pl-14">
                                    <!-- Date Dot -->
                                    <div class="absolute left-2.5 md:left-4.5 top-1 bg-indigo-500 rounded-full h-3 w-3 border-2 border-white dark:border-gray-800"></div>

                                    <div class="bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-750 p-5 rounded-lg">
                                        <div class="flex flex-col md:flex-row md:justify-between md:items-center border-b border-gray-200 dark:border-gray-700 pb-3 mb-4 gap-2">
                                            <div>
                                                <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                                    {{ \Carbon\Carbon::parse($record->created_at)->format('d F Y - H:i') }}
                                                </span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                    Dokter Pemeriksa: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $record->doctor->name }}</span>
                                                </div>
                                            </div>
                                            <!-- Diagnosis Badge -->
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                ICD-10: {{ $record->kode_icd_10 }}
                                            </span>
                                        </div>

                                        <!-- Detail Grid -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            <!-- Vital Signs -->
                                            <div class="md:col-span-1 bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-150 dark:border-gray-750 space-y-2 text-sm">
                                                <h4 class="font-bold text-gray-800 dark:text-gray-200 border-b border-gray-100 dark:border-gray-700 pb-1 flex items-center">
                                                    <svg class="h-4 w-4 mr-1 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                    </svg>
                                                    Tanda-Tanda Vital
                                                </h4>
                                                <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">Tekanan Darah:</span> <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $record->tekanan_darah }} mmHg</span></div>
                                                <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">Suhu Tubuh:</span> <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $record->suhu }} °C</span></div>
                                                <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">Denyut Nadi:</span> <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $record->nadi }} bpm</span></div>
                                                <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">Berat Badan:</span> <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $record->berat_badan }} kg</span></div>
                                                <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">Tinggi Badan:</span> <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $record->tinggi_badan }} cm</span></div>
                                            </div>

                                            <!-- Diagnosis & Actions -->
                                            <div class="md:col-span-2 space-y-4">
                                                <div>
                                                    <h5 class="text-sm font-bold text-gray-700 dark:text-gray-300">Keluhan Pasien:</h5>
                                                    <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $record->keluhan }}</p>
                                                </div>
                                                <div>
                                                    <h5 class="text-sm font-bold text-gray-700 dark:text-gray-300">Tindakan Medis:</h5>
                                                    <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $record->tindakan_medis }}</p>
                                                </div>

                                                <!-- Prescription -->
                                                @if($record->medicines->isNotEmpty())
                                                    <div class="mt-4 bg-indigo-50/50 dark:bg-indigo-950/20 p-4 rounded-lg border border-indigo-100 dark:border-indigo-900/30">
                                                        <h5 class="text-sm font-bold text-indigo-800 dark:text-indigo-300 flex items-center mb-2">
                                                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                            </svg>
                                                            Resep Digital (Obat)
                                                        </h5>
                                                        <ul class="divide-y divide-indigo-100/50 dark:divide-indigo-900/30 text-sm">
                                                            @foreach($record->medicines as $medicine)
                                                                <li class="py-1.5 flex justify-between">
                                                                    <span>
                                                                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $medicine->nama }}</span>
                                                                        <span class="text-xs text-gray-500 dark:text-gray-400 block md:inline md:ml-2">({{ $medicine->pivot->dosis }})</span>
                                                                    </span>
                                                                    <span class="font-semibold text-gray-700 dark:text-gray-300">
                                                                        {{ $medicine->pivot->jumlah }} {{ $medicine->satuan }}
                                                                    </span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Booking / Queue History -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Riwayat Pemesanan Antrean
                    </h3>
                    @if($queues->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4 bg-gray-50 dark:bg-gray-900/20 rounded">Belum ada riwayat pemesanan antrean.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-900/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nomor Antrean</th>
                                        <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal Kunjungan</th>
                                        <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sesi</th>
                                        <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dokter / Poli</th>
                                        <th class="px-6 py-3 text-left font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($queues as $queue)
                                        <tr>
                                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-gray-100">{{ $queue->nomor_antrean }}</td>
                                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($queue->tanggal)->format('d-m-Y') }}</td>
                                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $queue->sesi }}</td>
                                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $queue->doctor->name }}</td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $statusColors = [
                                                        'Menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                                        'Diperiksa' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                                        'Selesai' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                                        'Batal' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                                    ];
                                                    $color = $statusColors[$queue->status] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $color }}">
                                                    {{ $queue->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
