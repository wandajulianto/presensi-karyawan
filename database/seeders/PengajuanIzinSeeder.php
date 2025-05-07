<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengajuanIzinSeeder extends Seeder
{
    public function run()
    {
        DB::table('pengajuan_izins')->insert([
            [
                'id' => 17,
                'nik' => 12345,
                'tanggal_izin' => '2025-05-06',
                'status' => 'i',
                'keterangan' => 'menunggu',
                'status_approved' => 1,
                'created_at' => '2025-05-05 18:28:51',
                'updated_at' => '2025-05-05 18:28:51'
            ],
            [
                'id' => 18,
                'nik' => 67890,
                'tanggal_izin' => '2025-05-07',
                'status' => 's',
                'keterangan' => 'Disetujui',
                'status_approved' => 1,
                'created_at' => '2025-05-05 18:29:06',
                'updated_at' => '2025-05-05 18:29:06'
            ],
            [
                'id' => 19,
                'nik' => 12345,
                'tanggal_izin' => '2025-05-07',
                'status' => 'i',
                'keterangan' => 'Duka',
                'status_approved' => 1,
                'created_at' => '2025-05-05 18:29:19',
                'updated_at' => '2025-05-05 18:29:19'
            ]
        ]);
    }
}