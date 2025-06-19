<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Departemen;
use App\Models\Kantor;
use App\Models\KopSurat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\KopSuratTrait;

class MonitorPresensiController extends Controller
{
    use KopSuratTrait;
    public function index(Request $request)
    {
        $query = Presensi::with(['karyawan.departemen'])
            ->select('presensi.*')
            ->leftJoin('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->leftJoin('departemens', 'karyawan.kode_departemen', '=', 'departemens.kode_departemen');

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('presensi.tanggal_presensi', $request->tanggal);
        }

        // Filter berdasarkan nama karyawan
        if ($request->filled('nama_lengkap')) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        // Filter berdasarkan departemen
        if ($request->filled('departemen')) {
            $query->where('departemens.kode_departemen', $request->departemen);
        }

        // Ambil data presensi dengan pagination
        $presensis = $query->orderBy('presensi.tanggal_presensi', 'desc')
            ->orderBy('presensi.jam_masuk', 'desc')
            ->paginate(10);

        // Variabel untuk menghitung total jam keterlambatan
        $totalDetikTerlambat = 0;
        $jumlahKaryawanTerlambat = 0;

        // Tambahkan perhitungan jam keterlambatan untuk setiap record
        foreach ($presensis as $presensi) {
            $presensi->jam_keterlambatan = $this->hitungJamKeterlambatan($presensi->jam_masuk);
            
            // Hitung total detik terlambat jika karyawan terlambat
            if ($presensi->jam_masuk > '07:00:00') {
                $detikTerlambat = $this->hitungDetikKeterlambatan($presensi->jam_masuk);
                $totalDetikTerlambat += $detikTerlambat;
                $jumlahKaryawanTerlambat++;
            }
        }

        // Konversi total detik ke format jam:menit:detik
        $totalJamKeterlambatan = $this->konversiDetikKeJam($totalDetikTerlambat);
        
        // Hitung rata-rata jam keterlambatan
        $rataRataDetikTerlambat = $jumlahKaryawanTerlambat > 0 ? $totalDetikTerlambat / $jumlahKaryawanTerlambat : 0;
        $rataRataJamKeterlambatan = $this->konversiDetikKeJam($rataRataDetikTerlambat);

        // Ambil data departemen untuk dropdown filter
        $departemens = Departemen::orderBy('nama_departemen')->get();
        
        // Ambil data kantor utama
        $kantor = Kantor::getKantorUtama();

        return view('admin.dashboard.monitoringPresensi.index', compact(
            'presensis', 
            'departemens', 
            'totalJamKeterlambatan', 
            'jumlahKaryawanTerlambat',
            'rataRataJamKeterlambatan',
            'kantor'
        ));
    }

    /**
     * Menghitung jam keterlambatan berdasarkan jam masuk
     * Jam kerja standar dimulai pada 07:00:00
     */
    private function hitungJamKeterlambatan($jamMasuk)
    {
        // Jam kerja standar (07:00:00)
        $jamKerjaStandar = '07:00:00';
        
        // Jika jam masuk kurang dari atau sama dengan jam kerja standar, tidak terlambat
        if ($jamMasuk <= $jamKerjaStandar) {
            return '00:00:00';
        }

        // Hitung selisih waktu
        $jamStandar = Carbon::createFromTimeString($jamKerjaStandar);
        $jamMasukCarbon = Carbon::createFromTimeString($jamMasuk);
        
        // Hitung selisih dalam detik
        $selisihDetik = $jamMasukCarbon->diffInSeconds($jamStandar);
        
        // Konversi ke format jam:menit:detik
        $jam = floor($selisihDetik / 3600);
        $menit = floor(($selisihDetik % 3600) / 60);
        $detik = $selisihDetik % 60;
        
        return sprintf('%02d:%02d:%02d', $jam, $menit, $detik);
    }

    /**
     * Menghitung detik keterlambatan berdasarkan jam masuk
     */
    private function hitungDetikKeterlambatan($jamMasuk)
    {
        $jamKerjaStandar = '07:00:00';
        
        if ($jamMasuk <= $jamKerjaStandar) {
            return 0;
        }

        $jamStandar = Carbon::createFromTimeString($jamKerjaStandar);
        $jamMasukCarbon = Carbon::createFromTimeString($jamMasuk);
        
        return $jamMasukCarbon->diffInSeconds($jamStandar);
    }

    /**
     * Konversi detik ke format jam:menit:detik
     */
    private function konversiDetikKeJam($totalDetik)
    {
        $jam = floor($totalDetik / 3600);
        $menit = floor(($totalDetik % 3600) / 60);
        $detik = $totalDetik % 60;
        
        return sprintf('%02d:%02d:%02d', $jam, $menit, $detik);
    }

    /**
     * Export laporan jam keterlambatan ke CSV
     */
    public function exportKeterlambatan(Request $request)
    {
        $query = Presensi::with(['karyawan.departemen'])
            ->select('presensi.*')
            ->leftJoin('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->leftJoin('departemens', 'karyawan.kode_departemen', '=', 'departemens.kode_departemen')
            ->where('presensi.jam_masuk', '>', '07:00:00'); // Hanya yang terlambat

        // Terapkan filter yang sama dengan index
        if ($request->filled('tanggal')) {
            $query->whereDate('presensi.tanggal_presensi', $request->tanggal);
        }

        if ($request->filled('nama_lengkap')) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        if ($request->filled('departemen')) {
            $query->where('departemens.kode_departemen', $request->departemen);
        }

        $presensis = $query->orderBy('presensi.tanggal_presensi', 'desc')
            ->orderBy('presensi.jam_masuk', 'desc')
            ->get();

        // Set header untuk download CSV
        $fileName = 'laporan_keterlambatan_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($presensis, $request) {
            $file = fopen('php://output', 'w');
            
            // Header CSV dengan Kop Surat menggunakan trait
            $kopSurat = KopSurat::getActive();
            
            // Kop Surat untuk CSV
            fputcsv($file, ['LAPORAN KETERLAMBATAN PRESENSI']);
            fputcsv($file, [$kopSurat ? $kopSurat->nama_instansi : config('app.name', 'Nama Instansi')]);
            fputcsv($file, [$kopSurat ? $kopSurat->alamat_instansi : 'Alamat Instansi']);
            if ($kopSurat && $kopSurat->telepon_instansi) {
                fputcsv($file, ['Telp: ' . $kopSurat->telepon_instansi]);
            }
            fputcsv($file, ['']);
            if ($request->filled('tanggal')) {
                fputcsv($file, ['Tanggal: ' . Carbon::parse($request->tanggal)->translatedFormat('d F Y')]);
            }
            fputcsv($file, ['Tanggal Cetak: ' . Carbon::now()->translatedFormat('d F Y H:i:s')]);
            fputcsv($file, ['']);
            
            // Header Data CSV
            fputcsv($file, [
                'No',
                'Tanggal',
                'NIK',
                'Nama Karyawan',
                'Departemen',
                'Jam Masuk',
                'Jam Keterlambatan',
                'Status'
            ]);

            // Data CSV
            $no = 1;
            foreach ($presensis as $presensi) {
                $jamKeterlambatan = $this->hitungJamKeterlambatan($presensi->jam_masuk);
                
                fputcsv($file, [
                    $no++,
                    $presensi->tanggal_presensi,
                    $presensi->karyawan->nik,
                    $presensi->karyawan->nama_lengkap,
                    $presensi->karyawan->departemen->nama_departemen ?? 'Tidak Ada Departemen',
                    $presensi->jam_masuk,
                    $jamKeterlambatan,
                    'Terlambat'
                ]);
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

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Cetak laporan keterlambatan dalam bentuk PDF
     */
    public function cetakKeterlambatan(Request $request)
    {
        $query = Presensi::with(['karyawan.departemen'])
            ->select('presensi.*')
            ->leftJoin('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->leftJoin('departemens', 'karyawan.kode_departemen', '=', 'departemens.kode_departemen')
            ->where('presensi.jam_masuk', '>', '07:00:00'); // Hanya yang terlambat

        // Terapkan filter yang sama dengan index
        if ($request->filled('tanggal')) {
            $query->whereDate('presensi.tanggal_presensi', $request->tanggal);
        }

        if ($request->filled('nama_lengkap')) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        if ($request->filled('departemen')) {
            $query->where('departemens.kode_departemen', $request->departemen);
        }

        $presensis = $query->orderBy('presensi.tanggal_presensi', 'desc')
            ->orderBy('presensi.jam_masuk', 'desc')
            ->get();

        // Siapkan data filter untuk tampilan
        $filterTanggal = $request->tanggal ? Carbon::parse($request->tanggal)->translatedFormat('d F Y') : null;
        $filterNama = $request->nama_lengkap;
        $filterDepartemen = null;
        
        if ($request->filled('departemen')) {
            $dept = Departemen::where('kode_departemen', $request->departemen)->first();
            $filterDepartemen = $dept ? $dept->nama_departemen : 'Departemen tidak ditemukan';
        }

        // Ambil data untuk PDF menggunakan trait
        $title = 'Laporan Monitoring Keterlambatan';
        $period = $filterTanggal ? 'Tanggal: ' . $filterTanggal : 'Semua Periode';
        
        $data = $this->getPDFData($title, $period);
        $data['presensis'] = $presensis;
        $data['filterTanggal'] = $filterTanggal;
        $data['filterNama'] = $filterNama;
        $data['filterDepartemen'] = $filterDepartemen;

        // Generate PDF
        $pdf = Pdf::loadView('admin.dashboard.monitoringPresensi.pdf', $data)
            ->setPaper('a4', 'landscape');

        $fileName = 'monitoring_keterlambatan_' . date('Y-m-d_H-i-s') . '.pdf';
        return $pdf->download($fileName);
    }
}
