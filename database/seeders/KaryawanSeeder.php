<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('karyawan')->insert([
            [
                'nik' => 12345,
                'nama_lengkap' => 'Wanda Julianto',
                'jabatan' => 'Admin',
                'no_hp' => '081234567890',
                'password' => bcrypt('password1'),
                'remember_token' => null,
            ],
            [
                'nik' => 67890,
                'nama_lengkap' => 'Dewi Lestari',
                'jabatan' => 'Staff',
                'no_hp' => '081298765432',
                'password' => bcrypt('password2'),
                'remember_token' => null,
            ],
        ]);
    }
}
