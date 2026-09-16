<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Utama
        User::create([
            'name'     => 'Administrator Sekolah',
            'username' => 'admin',
            'email'    => 'admin@sekolah.sch.id',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // 2. Akun Sample Guru (NIP sebagai Username, Tgl Lahir sebagai Password)
        // Contoh Tgl Lahir Guru: 15 Mei 1985 -> Password: "15051985"
        User::create([
            'name'     => 'Budi Santoso, S.Pd',
            'username' => '198505152010011001', // NIP
            'email'    => 'budi@sekolah.sch.id',
            'password' => Hash::make('15051985'),
            'role'     => 'guru',
        ]);

        // 3. Akun Sample Siswa (NISN sebagai Username, Tgl Lahir sebagai Password)
        // Contoh Tgl Lahir Siswa: 20 Agustus 2008 -> Password: "20082008"
        User::create([
            'name'     => 'Dafa Firdaus',
            'username' => '0081234567', // NISN
            'email'    => null,
            'password' => Hash::make('20082008'),
            'role'     => 'siswa',
        ]);
    }
}
