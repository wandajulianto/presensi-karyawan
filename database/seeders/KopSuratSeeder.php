<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KopSurat;

class KopSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KopSurat::create([
            'nama_instansi' => 'PT. ARISYA TEKNOLOGI INDONESIA',
            'alamat_instansi' => 'Jl. Raya Ciamis No. 123, Kecamatan Ciamis, Kabupaten Ciamis, Jawa Barat 46211',
            'telepon_instansi' => '(0265) 123456',
            'email_instansi' => 'info@arisya.co.id',
            'website_instansi' => 'www.arisya.co.id',
            'logo_instansi' => null, // akan menggunakan default logo
            'nama_pimpinan' => 'John Doe, S.T., M.T.',
            'jabatan_pimpinan' => 'Direktur Utama',
            'nip_pimpinan' => '198501012010011001',
            'is_active' => true
        ]);
    }
}
