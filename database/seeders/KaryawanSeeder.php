<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Karyawan;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $existingNik = Karyawan::pluck('nik')->toArray();
        
        $departemen = ['IT', 'HRD', 'FIN', 'OPS', 'MKT', 'PRC'];
        $jabatan = ['Admin', 'Manager', 'Supervisor', 'Staff', 'Operator'];
        
        $newKaryawanData = [];

        // Counter untuk NIK baru (mulai dari NIK terbesar + 1)
        $lastNik = !empty($existingNik) ? max($existingNik) : 10000;
        $startNik = $lastNik + 1;

        // Generate 26 karyawan baru
        for ($i = 0; $i < 26; $i++) {
            $newKaryawanData[] = [
                'nik' => $startNik + $i,
                'nama_lengkap' => $this->generateRandomName(),
                'jabatan' => $jabatan[array_rand($jabatan)],
                'no_hp' => '08' . mt_rand(100000000, 999999999),
                'password' => Hash::make('password123'),
                'kode_departemen' => ($i % 7 == 0) ? null : $departemen[array_rand($departemen)],
                'remember_token' => null,
            ];
        }

        // Masukkan data baru saja
        DB::table('karyawan')->insert($newKaryawanData);
    }

    private function generateRandomName(): string
    {
        $firstNames = ['Ahmad', 'Budi', 'Citra', 'Dewi', 'Eka', 'Fajar', 'Gita', 'Hadi', 'Indra', 'Joko'];
        $lastNames = ['Santoso', 'Wijaya', 'Kusuma', 'Purnama', 'Sari', 'Nugroho', 'Putra', 'Lestari', 'Kurniawan', 'Dharmawan'];
        
        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }
}