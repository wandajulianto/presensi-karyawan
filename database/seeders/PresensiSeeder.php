<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PresensiSeeder extends Seeder
{
    public function run()
    {
        DB::table('presensi')->insert([
            [
                'id' => 22,
                'nik' => 112233,
                'tanggal_presensi' => '2025-05-07',
                'jam_masuk' => '07:36:11',
                'jam_keluar' => '20:42:57',
                'foto_masuk' => '12345-2025-05-03-in.png',
                'foto_keluar' => '12345-2025-05-03-out.png',
                'lokasi_masuk' => '-7.3334784,108.2228736',
                'lokasi_keluar' => '-7.3334784,108.2228736'
            ],
            [
                'id' => 23,
                'nik' => 12345,
                'tanggal_presensi' => '2025-05-07',
                'jam_masuk' => '06:07:47',
                'jam_keluar' => '16:28:17',
                'foto_masuk' => '12345-2025-05-04-in.png',
                'foto_keluar' => '12345-2025-05-04-out.png',
                'lokasi_masuk' => '-7.3334784,108.2228736',
                'lokasi_keluar' => '-7.3334784,108.2228736'
            ]
        ]);
    }
}