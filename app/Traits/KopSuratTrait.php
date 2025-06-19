<?php

namespace App\Traits;

use App\Models\KopSurat;
use Carbon\Carbon;

trait KopSuratTrait
{
    /**
     * Get active kop surat configuration
     */
    protected function getKopSurat()
    {
        return KopSurat::getActive();
    }

    /**
     * Add header (kop surat) to CSV export
     */
    protected function addCSVHeader($file, $title, $period = null, $kopSurat = null)
    {
        if (!$kopSurat) {
            $kopSurat = $this->getKopSurat();
        }

        // Kop Surat untuk CSV
        fputcsv($file, [strtoupper($title)]);
        fputcsv($file, [$kopSurat ? $kopSurat->nama_instansi : config('app.name', 'Nama Instansi')]);
        fputcsv($file, [$kopSurat ? $kopSurat->alamat_instansi : 'Alamat Instansi']);
        
        if ($kopSurat && $kopSurat->telepon_instansi) {
            fputcsv($file, ['Telp: ' . $kopSurat->telepon_instansi]);
        }
        
        fputcsv($file, ['']);
        
        if ($period) {
            fputcsv($file, ['Periode: ' . $period]);
        }
        
        fputcsv($file, ['Tanggal Cetak: ' . Carbon::now()->translatedFormat('d F Y H:i:s')]);
        fputcsv($file, ['']);
    }

    /**
     * Add footer (signature) to CSV export
     */
    protected function addCSVFooter($file, $kopSurat = null)
    {
        if (!$kopSurat) {
            $kopSurat = $this->getKopSurat();
        }

        // Footer CSV - Tanda Tangan
        fputcsv($file, ['']);
        fputcsv($file, ['']);
        fputcsv($file, [$this->extractCityFromAddress($kopSurat ? $kopSurat->alamat_instansi : null) . ', ' . Carbon::now()->translatedFormat('d F Y')]);
        fputcsv($file, ['']);
        fputcsv($file, [$kopSurat ? $kopSurat->jabatan_pimpinan : 'Pimpinan']);
        fputcsv($file, ['']);
        fputcsv($file, ['']);
        fputcsv($file, ['']);
        fputcsv($file, [$kopSurat ? $kopSurat->nama_pimpinan : 'Nama Pimpinan']);
        
        if ($kopSurat && $kopSurat->nip_pimpinan) {
            fputcsv($file, ['NIP: ' . $kopSurat->nip_pimpinan]);
        }
    }

    /**
     * Get PDF data with kop surat
     */
    protected function getPDFData($title = null, $period = null, $additionalData = [])
    {
        $kopSurat = $this->getKopSurat();
        
        $data = [
            'kopSurat' => $kopSurat,
        ];
        
        if ($title) {
            $data['title'] = $title;
        }
        
        if ($period) {
            $data['period'] = $period;
        }
        
        return array_merge($data, $additionalData);
    }

    /**
     * Generate standardized period text
     */
    protected function generatePeriodText($bulan = null, $tahun = null, $tanggal = null)
    {
        if ($tanggal) {
            return 'Tanggal: ' . Carbon::parse($tanggal)->translatedFormat('d F Y');
        }
        
        if ($bulan && $tahun) {
            return Carbon::create()->month((int) $bulan)->translatedFormat('F') . ' ' . $tahun;
        }
        
        if ($tahun) {
            return 'Tahun ' . $tahun;
        }
        
        return 'Semua Periode';
    }

    /**
     * Extract city/kabupaten name from address string
     * Format alamat Indonesia: "Jalan, Kecamatan, Kabupaten/Kota, Provinsi"
     */
    protected function extractCityFromAddress($alamat)
    {
        if (!$alamat) {
            return 'Kota';
        }

        $parts = array_map('trim', explode(',', $alamat));
        
        // Cari bagian yang mengandung "Kabupaten" atau "Kota"
        foreach ($parts as $part) {
            if (stripos($part, 'Kabupaten') !== false) {
                return str_ireplace('Kabupaten ', '', $part);
            }
            if (stripos($part, 'Kota') !== false) {
                return str_ireplace('Kota ', '', $part);
            }
        }
        
        // Jika tidak ada "Kabupaten" atau "Kota", ambil bagian ketiga (index 2) sebagai fallback
        if (count($parts) >= 3) {
            return $parts[2];
        }
        
        // Fallback terakhir
        return 'Kota';
    }
} 