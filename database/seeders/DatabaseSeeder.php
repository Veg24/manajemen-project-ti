<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\DoctorSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users with Roles
        $admin = User::create([
            'name' => 'Admin Klinik',
            'email' => 'admin@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'Admin',
        ]);

        $resepsionis = User::create([
            'name' => 'Resepsionis Klinik',
            'email' => 'resepsionis@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'Resepsionis',
        ]);

        $dokter1 = User::create([
            'name' => 'Dr. Budi Santoso (Poli Umum)',
            'email' => 'dokter1@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'Dokter',
        ]);

        $dokter2 = User::create([
            'name' => 'Dr. Siti Aminah (Poli Anak)',
            'email' => 'dokter2@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'Dokter',
        ]);

        $perawat = User::create([
            'name' => 'Suster Amelia',
            'email' => 'perawat@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'Perawat',
        ]);

        $apoteker = User::create([
            'name' => 'Apoteker Rian',
            'email' => 'apoteker@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'Apoteker',
        ]);

        // A Pasien user
        $userPasien = User::create([
            'name' => 'Budi Pekerti',
            'email' => 'pasien@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'Pasien',
        ]);

        // 2. Seed Patient profile linked to the Pasien user
        $pasien = Patient::create([
            'user_id' => $userPasien->id,
            'nama' => 'Budi Pekerti',
            'NIK' => '1234567890123456',
            'tgl_lahir' => '1995-08-17',
            'alamat' => 'Jl. Merdeka No. 17, Jakarta',
            'no_telp' => '081234567890',
        ]);

        // Add another patient who doesn't have an online user account yet
        Patient::create([
            'user_id' => null,
            'nama' => 'Agus Wijaya',
            'NIK' => '3201234567890123',
            'tgl_lahir' => '1988-12-05',
            'alamat' => 'Jl. Mawar No. 45, Bogor',
            'no_telp' => '087788990011',
        ]);

        // 3. Seed Doctor Schedules
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        foreach ($days as $day) {
            DoctorSchedule::create([
                'doctor_id' => $dokter1->id,
                'hari' => $day,
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '12:00:00',
            ]);

            DoctorSchedule::create([
                'doctor_id' => $dokter2->id,
                'hari' => $day,
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '17:00:00',
            ]);
        }

        // 4. Seed Medicines (including some low stock items to test warnings)
        Medicine::create([
            'nama' => 'Paracetamol 500mg',
            'kategori' => 'Analgesik',
            'satuan' => 'Tablet',
            'harga' => 500.00,
            'stok' => 120,
            'min_stock' => 20,
        ]);

        Medicine::create([
            'nama' => 'Amoxicillin 500mg',
            'kategori' => 'Antibiotik',
            'satuan' => 'Tablet',
            'harga' => 1500.00,
            'stok' => 45,
            'min_stock' => 15,
        ]);

        Medicine::create([
            'nama' => 'Ibuprofen 400mg',
            'kategori' => 'Analgesik',
            'satuan' => 'Tablet',
            'harga' => 800.00,
            'stok' => 8, // Low stock! (< min_stock 15)
            'min_stock' => 15,
        ]);

        Medicine::create([
            'nama' => 'Obat Batuk Sirup OBH',
            'kategori' => 'Sirup',
            'satuan' => 'Botol',
            'harga' => 15000.00,
            'stok' => 4, // Low stock! (< min_stock 10)
            'min_stock' => 10,
        ]);

        Medicine::create([
            'nama' => 'Cetirizine 10mg',
            'kategori' => 'Antihistamin',
            'satuan' => 'Tablet',
            'harga' => 1000.00,
            'stok' => 100,
            'min_stock' => 10,
        ]);
    }
}
