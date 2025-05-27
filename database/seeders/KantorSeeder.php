<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kantor;

class KantorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kantor::create([
            'nama_kantor' => 'Kantor Pusat',
            'alamat' => 'Jl. Raya Indihiang No. 123, Indihiang, Tasikmalaya, Jawa Barat 46151',
            'latitude' => -7.333174936756437,
            'longitude' => 108.2197967875599,
            'radius_meter' => 20,
            'kode_kantor' => 'KP001',
            'deskripsi' => 'Kantor pusat utama perusahaan',
            'is_active' => true,
            'timezone' => 'Asia/Jakarta',
            'jam_masuk' => '07:00:00',
            'jam_keluar' => '17:00:00'
        ]);

        // Contoh kantor cabang (opsional)
        Kantor::create([
            'nama_kantor' => 'Kantor Cabang Jakarta',
            'alamat' => 'Jl. Sudirman No. 45, Jakarta Pusat, DKI Jakarta 10220',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'radius_meter' => 30,
            'kode_kantor' => 'CB001',
            'deskripsi' => 'Kantor cabang Jakarta',
            'is_active' => true,
            'timezone' => 'Asia/Jakarta',
            'jam_masuk' => '08:00:00',
            'jam_keluar' => '17:00:00'
        ]);
    }
}
