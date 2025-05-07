<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartemenSeeder extends Seeder
{
    public function run()
    {
        DB::table('departemens')->insert([
            [
                'kode_departemen' => 'IT',
                'nama_departemen' => 'Information Technology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_departemen' => 'HRD',
                'nama_departemen' => 'Human Resources',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_departemen' => 'FIN',
                'nama_departemen' => 'Finance',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_departemen' => 'OPS',
                'nama_departemen' => 'Operations',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_departemen' => 'MKT',
                'nama_departemen' => 'Marketing',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_departemen' => 'PRC',
                'nama_departemen' => 'Procurement',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}