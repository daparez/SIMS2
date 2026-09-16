<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JamPelajaran;
use Illuminate\Support\Facades\DB;

class JamPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data lama
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        JamPelajaran::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. JADWAL SENIN - KAMIS (Masuk 08.10 - Pulang 14.45)
        $hariSeninKamis = ['Senin', 'Selasa', 'Rabu', 'Kamis'];
        foreach ($hariSeninKamis as $hari) {
            // Jam 1 & 2 (08.10 - 09.50) @ 50 menit
            JamPelajaran::create(['hari' => $hari, 'jam_ke' => 1, 'jam_mulai' => '08:10:00', 'jam_selesai' => '09:00:00', 'is_istirahat' => false]);
            JamPelajaran::create(['hari' => $hari, 'jam_ke' => 2, 'jam_mulai' => '09:00:00', 'jam_selesai' => '09:50:00', 'is_istirahat' => false]);
            
            // ISTIRAHAT I (09.50 - 10.10)
            JamPelajaran::create(['hari' => $hari, 'jam_ke' => 3, 'jam_mulai' => '09:50:00', 'jam_selesai' => '10:10:00', 'is_istirahat' => true]);

            // Jam 4 & 5 (10.10 - 11.50)
            JamPelajaran::create(['hari' => $hari, 'jam_ke' => 4, 'jam_mulai' => '10:10:00', 'jam_selesai' => '11:00:00', 'is_istirahat' => false]);
            JamPelajaran::create(['hari' => $hari, 'jam_ke' => 5, 'jam_mulai' => '11:00:00', 'jam_selesai' => '11:50:00', 'is_istirahat' => false]);

            // ISTIRAHAT II / ISHOMA (11.50 - 12.30)
            JamPelajaran::create(['hari' => $hari, 'jam_ke' => 6, 'jam_mulai' => '11:50:00', 'jam_selesai' => '12:30:00', 'is_istirahat' => true]);

            // Jam 7, 8, 9 (12.30 - 14.45) @ 45 menit
            JamPelajaran::create(['hari' => $hari, 'jam_ke' => 7, 'jam_mulai' => '12:30:00', 'jam_selesai' => '13:15:00', 'is_istirahat' => false]);
            JamPelajaran::create(['hari' => $hari, 'jam_ke' => 8, 'jam_mulai' => '13:15:00', 'jam_selesai' => '14:00:00', 'is_istirahat' => false]);
            JamPelajaran::create(['hari' => $hari, 'jam_ke' => 9, 'jam_mulai' => '14:00:00', 'jam_selesai' => '14:45:00', 'is_istirahat' => false]);
        }

        // 2. JADWAL JUMAT (Masuk 08.10 - Pulang 11.20)
        // Jam 1 & 2 (08.10 - 09.50)
        JamPelajaran::create(['hari' => 'Jumat', 'jam_ke' => 1, 'jam_mulai' => '08:10:00', 'jam_selesai' => '09:00:00', 'is_istirahat' => false]);
        JamPelajaran::create(['hari' => 'Jumat', 'jam_ke' => 2, 'jam_mulai' => '09:00:00', 'jam_selesai' => '09:50:00', 'is_istirahat' => false]);
        
        // ISTIRAHAT (09.50 - 10.10)
        JamPelajaran::create(['hari' => 'Jumat', 'jam_ke' => 3, 'jam_mulai' => '09:50:00', 'jam_selesai' => '10:10:00', 'is_istirahat' => true]);

        // Jam 4 & 5 (10.10 - 11.20)
        JamPelajaran::create(['hari' => 'Jumat', 'jam_ke' => 4, 'jam_mulai' => '10:10:00', 'jam_selesai' => '10:45:00', 'is_istirahat' => false]);
        JamPelajaran::create(['hari' => 'Jumat', 'jam_ke' => 5, 'jam_mulai' => '10:45:00', 'jam_selesai' => '11:20:00', 'is_istirahat' => false]);
    }
}