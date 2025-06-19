<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Departemen;
use App\Models\KopSurat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\KopSuratTrait;

class LaporanPresensiController extends Controller
{
    use KopSuratTrait;
    public function index(Request $request)
    {
        // Ambil data departemen untuk dropdown filter
        $departemens = Departemen::orderBy('nama_departemen')->get();

        // Jika tidak ada filter, hanya tampilkan form
        if (!$request->anyFilled(['bulan', 'tahun', 'nama_karyawan', 'departemen'])) {
            return view('admin.dashboard.laporan.presensi.index', compact('departemens'));
        }

        // Build query untuk laporan presensi
        $query = Presensi::with(['karyawan.departemen'])
            ->select('presensi.*')
            ->leftJoin('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->leftJoin('departemens', 'karyawan.kode_departemen', '=', 'departemens.kode_departemen');

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('presensi.tanggal_presensi', $request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->whereYear('presensi.tanggal_presensi', $request->tahun);
        }

        // Filter berdasarkan nama karyawan
        if ($request->filled('nama_karyawan')) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }

        // Filter berdasarkan departemen
        if ($request->filled('departemen')) {
            $query->where('departemens.kode_departemen', $request->departemen);
        }

        // Ambil data presensi
        $laporan_presensi = $query->orderBy('presensi.tanggal_presensi', 'desc')
            ->orderBy('presensi.jam_masuk', 'asc')
            ->get();

        // Hitung summary statistik
        $summary = $this->hitungSummaryLaporan($laporan_presensi, $request);

        return view('admin.dashboard.laporan.presensi.index', compact(
            'departemens', 
            'laporan_presensi', 
            'summary'
        ));
    }

    /**
     * Menghitung summary statistik laporan
     */
    private function hitungSummaryLaporan($presensi_data, $request)
    {
        // Hitung total hari kerja dalam periode yang dipilih
        $total_hari_kerja = 0;
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $bulan = $request->bulan;
            $tahun = $request->tahun;
            $total_hari_kerja = $this->hitungHariKerja($bulan, $tahun);
        }

        // Hitung statistik dari data presensi
        $total_hadir = $presensi_data->count();
        $total_terlambat = $presensi_data->where('jam_masuk', '>', '07:00:00')->count();
        
        // Hitung persentase kehadiran
        $persentase_kehadiran = $total_hari_kerja > 0 ? round(($total_hadir / $total_hari_kerja) * 100, 1) : 0;

        return [
            'total_hari_kerja' => $total_hari_kerja,
            'total_hadir' => $total_hadir,
            'total_terlambat' => $total_terlambat,
            'persentase_kehadiran' => $persentase_kehadiran
        ];
    }

    /**
     * Menghitung jumlah hari kerja (Senin-Jumat) dalam suatu bulan
     */
    private function hitungHariKerja($bulan, $tahun)
    {
        $start = Carbon::create($tahun, $bulan, 1);
        $end = $start->copy()->endOfMonth();
        
        $hari_kerja = 0;
        for ($date = $start->copy(); $date <= $end; $date->addDay()) {
            // Senin = 1, Jumat = 5 (hari kerja)
            if ($date->dayOfWeek >= 1 && $date->dayOfWeek <= 5) {
                $hari_kerja++;
            }
        }
        
        return $hari_kerja;
    }

    /**
     * Export laporan presensi ke Excel/CSV
     */
    public function export(Request $request)
    {
        // Build query yang sama dengan index
        $query = Presensi::with(['karyawan.departemen'])
            ->select('presensi.*')
            ->leftJoin('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->leftJoin('departemens', 'karyawan.kode_departemen', '=', 'departemens.kode_departemen');

        // Terapkan filter yang sama
        if ($request->filled('bulan')) {
            $query->whereMonth('presensi.tanggal_presensi', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('presensi.tanggal_presensi', $request->tahun);
        }

        if ($request->filled('nama_karyawan')) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }

        if ($request->filled('departemen')) {
            $query->where('departemens.kode_departemen', $request->departemen);
        }

        $laporan_presensi = $query->orderBy('presensi.tanggal_presensi', 'desc')
            ->orderBy('presensi.jam_masuk', 'asc')
            ->get();

        // Set header untuk download CSV
        $fileName = 'laporan_presensi_' . 
                   ($request->bulan ? $request->bulan : 'semua') . '_' . 
                   ($request->tahun ? $request->tahun : date('Y')) . '_' . 
                   date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($laporan_presensi, $request) {
            $file = fopen('php://output', 'w');
            
            // Header CSV dengan Kop Surat
            $kopSurat = KopSurat::getActive();
            
            fputcsv($file, ['LAPORAN PRESENSI KARYAWAN']);
            fputcsv($file, [$kopSurat ? $kopSurat->nama_instansi : config('app.name', 'Nama Instansi')]);
            fputcsv($file, [$kopSurat ? $kopSurat->alamat_instansi : 'Alamat Instansi']);
            if ($kopSurat && $kopSurat->telepon_instansi) {
                fputcsv($file, ['Telp: ' . $kopSurat->telepon_instansi]);
            }
            fputcsv($file, ['']);
            fputcsv($file, ['Periode: ' . 
                ($request->bulan ? Carbon::create()->month((int) $request->bulan)->translatedFormat('F') : 'Semua Bulan') . ' ' .
                ($request->tahun ? $request->tahun : date('Y'))
            ]);
            fputcsv($file, ['Tanggal Cetak: ' . Carbon::now()->translatedFormat('d F Y H:i:s')]);
            fputcsv($file, ['']);
            
            // Header Data CSV
            fputcsv($file, [
                'No',
                'NIK',
                'Nama Karyawan',
                'Departemen',
                'Tanggal',
                'Jam Masuk',
                'Jam Keluar',
                'Status Kehadiran',
                'Keterlambatan (HH:MM:SS)',
                'Keterangan'
            ]);

            // Data CSV
            $no = 1;
            foreach ($laporan_presensi as $presensi) {
                // Hitung keterlambatan
                $keterlambatan = '-';
                $status_kehadiran = 'Tepat Waktu';
                
                if ($presensi->jam_masuk > '07:00:00') {
                    $jamStandar = Carbon::createFromTimeString('07:00:00');
                    $jamMasuk = Carbon::createFromTimeString($presensi->jam_masuk);
                    $keterlambatan = $jamMasuk->diff($jamStandar)->format('%H:%I:%S');
                    $status_kehadiran = 'Terlambat';
                }
                
                fputcsv($file, [
                    $no++,
                    $presensi->nik,
                    $presensi->karyawan->nama_lengkap,
                    $presensi->karyawan->departemen->nama_departemen ?? 'Tidak Ada Departemen',
                    Carbon::parse($presensi->tanggal_presensi)->format('d/m/Y'),
                    $presensi->jam_masuk,
                    $presensi->jam_keluar ?? 'Belum Pulang',
                    $status_kehadiran,
                    $keterlambatan,
                    $status_kehadiran == 'Terlambat' ? 'Karyawan datang terlambat' : 'Karyawan datang tepat waktu'
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
     * Halaman rekap presensi per karyawan
     */
    public function exportKeterlambatan(Request $request)
    {
        // Ambil data departemen untuk dropdown filter
        $departemens = Departemen::orderBy('nama_departemen')->get();

        // Jika tidak ada filter, hanya tampilkan form
        if (!$request->anyFilled(['bulan', 'tahun', 'departemen'])) {
            return view('admin.dashboard.laporan.rekap.index', compact('departemens'));
        }

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Build query untuk rekap per karyawan
        $query = DB::table('karyawan')
            ->leftJoin('departemens', 'karyawan.kode_departemen', '=', 'departemens.kode_departemen')
            ->leftJoin('presensi', 'karyawan.nik', '=', 'presensi.nik')
            ->select(
                'karyawan.nik',
                'karyawan.nama_lengkap',
                'departemens.nama_departemen',
                DB::raw('COUNT(CASE WHEN presensi.tanggal_presensi IS NOT NULL THEN 1 END) as total_hadir'),
                DB::raw('COUNT(CASE WHEN presensi.jam_masuk > "07:00:00" THEN 1 END) as total_terlambat')
            );

        // Filter berdasarkan bulan dan tahun jika ada
        if ($bulan) {
            $query->whereMonth('presensi.tanggal_presensi', $bulan);
        }
        if ($tahun) {
            $query->whereYear('presensi.tanggal_presensi', $tahun);
        }

        // Filter berdasarkan departemen
        if ($request->filled('departemen')) {
            $query->where('departemens.kode_departemen', $request->departemen);
        }

        $rekap_karyawan = $query->groupBy('karyawan.nik', 'karyawan.nama_lengkap', 'departemens.nama_departemen')
            ->orderBy('karyawan.nama_lengkap')
            ->get();

        // Hitung summary
        $summary = $this->hitungSummaryRekap($rekap_karyawan, $bulan, $tahun);

        return view('admin.dashboard.laporan.rekap.index', compact(
            'departemens',
            'rekap_karyawan',
            'summary'
        ));
    }

    /**
     * Menghitung summary untuk rekap
     */
    private function hitungSummaryRekap($rekap_karyawan, $bulan, $tahun)
    {
        $total_karyawan = $rekap_karyawan->count();
        $total_keterlambatan = $rekap_karyawan->sum('total_terlambat');
        
        // Hitung total hari kerja
        $total_hari_kerja = 0;
        if ($bulan && $tahun) {
            $total_hari_kerja = $this->hitungHariKerja($bulan, $tahun);
        }

        // Hitung rata-rata kehadiran
        $total_kehadiran = $rekap_karyawan->sum('total_hadir');
        $rata_rata_kehadiran = $total_karyawan > 0 && $total_hari_kerja > 0 
            ? round(($total_kehadiran / ($total_karyawan * $total_hari_kerja)) * 100, 1) 
            : 0;

        return [
            'total_karyawan' => $total_karyawan,
            'total_keterlambatan' => $total_keterlambatan,
            'total_hari_kerja' => $total_hari_kerja,
            'rata_rata_kehadiran' => $rata_rata_kehadiran
        ];
    }

    /**
     * Export rekap presensi ke CSV
     */
    public function exportRekap(Request $request)
    {
        // Build query yang sama dengan rekap
        $query = DB::table('karyawan')
            ->leftJoin('departemens', 'karyawan.kode_departemen', '=', 'departemens.kode_departemen')
            ->leftJoin('presensi', 'karyawan.nik', '=', 'presensi.nik')
            ->select(
                'karyawan.nik',
                'karyawan.nama_lengkap',
                'departemens.nama_departemen',
                DB::raw('COUNT(CASE WHEN presensi.tanggal_presensi IS NOT NULL THEN 1 END) as total_hadir'),
                DB::raw('COUNT(CASE WHEN presensi.jam_masuk > "07:00:00" THEN 1 END) as total_terlambat')
            );

        // Terapkan filter
        if ($request->filled('bulan')) {
            $query->whereMonth('presensi.tanggal_presensi', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('presensi.tanggal_presensi', $request->tahun);
        }
        if ($request->filled('departemen')) {
            $query->where('departemens.kode_departemen', $request->departemen);
        }

        $rekap_karyawan = $query->groupBy('karyawan.nik', 'karyawan.nama_lengkap', 'departemens.nama_departemen')
            ->orderBy('karyawan.nama_lengkap')
            ->get();

        // Hitung total hari kerja untuk persentase
        $total_hari_kerja = 0;
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $total_hari_kerja = $this->hitungHariKerja($request->bulan, $request->tahun);
        }

        // Set header untuk download CSV
        $fileName = 'rekap_presensi_' . 
                   ($request->bulan ? $request->bulan : 'semua') . '_' . 
                   ($request->tahun ? $request->tahun : date('Y')) . '_' . 
                   date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($rekap_karyawan, $total_hari_kerja, $request) {
            $file = fopen('php://output', 'w');
            
            // Header CSV dengan Kop Surat
            $kopSurat = KopSurat::getActive();
            
            fputcsv($file, ['REKAP PRESENSI KARYAWAN']);
            fputcsv($file, [$kopSurat ? $kopSurat->nama_instansi : config('app.name', 'Nama Instansi')]);
            fputcsv($file, [$kopSurat ? $kopSurat->alamat_instansi : 'Alamat Instansi']);
            if ($kopSurat && $kopSurat->telepon_instansi) {
                fputcsv($file, ['Telp: ' . $kopSurat->telepon_instansi]);
            }
            fputcsv($file, ['']);
            fputcsv($file, ['Periode: ' . 
                ($request->bulan ? Carbon::create()->month((int) $request->bulan)->translatedFormat('F') : 'Semua Bulan') . ' ' .
                ($request->tahun ? $request->tahun : date('Y'))
            ]);
            fputcsv($file, ['Tanggal Cetak: ' . Carbon::now()->translatedFormat('d F Y H:i:s')]);
            fputcsv($file, ['']);
            
            // Header Data CSV
            fputcsv($file, [
                'No',
                'NIK',
                'Nama Karyawan',
                'Departemen',
                'Total Hadir',
                'Total Terlambat',
                'Persentase Kehadiran (%)',
                'Status Kehadiran'
            ]);

            // Data CSV
            $no = 1;
            foreach ($rekap_karyawan as $karyawan) {
                $persentase = $total_hari_kerja > 0 ? round(($karyawan->total_hadir / $total_hari_kerja) * 100, 1) : 0;
                
                if ($persentase >= 90) {
                    $status = 'Sangat Baik';
                } elseif ($persentase >= 75) {
                    $status = 'Baik';
                } else {
                    $status = 'Perlu Perhatian';
                }
                
                fputcsv($file, [
                    $no++,
                    $karyawan->nik,
                    $karyawan->nama_lengkap,
                    $karyawan->nama_departemen ?? 'Tidak Ada Departemen',
                    $karyawan->total_hadir,
                    $karyawan->total_terlambat,
                    $persentase,
                    $status
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
     * Cetak laporan presensi dalam bentuk PDF
     */
    public function cetakPresensi(Request $request)
    {
        // Build query presensi sama seperti di index method
        $query = Presensi::with(['karyawan.departemen'])
            ->whereNotNull('tanggal_presensi')
            ->orderBy('tanggal_presensi', 'desc');

        // Terapkan filter dan buat info filter
        $filter_info = [];
        
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal_presensi', $request->bulan)
                  ->whereYear('tanggal_presensi', $request->tahun);
            $bulanNama = \Carbon\Carbon::create()->month((int) $request->bulan)->translatedFormat('F');
            $filter_info[] = "Bulan: {$bulanNama} {$request->tahun}";
        } elseif ($request->filled('tahun')) {
            $query->whereYear('tanggal_presensi', $request->tahun);
            $filter_info[] = "Tahun: {$request->tahun}";
        }

        if ($request->filled('nama_karyawan')) {
            $query->whereHas('karyawan', function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
            });
            $filter_info[] = "Nama: {$request->nama_karyawan}";
        }

        if ($request->filled('departemen')) {
            $query->whereHas('karyawan', function($q) use ($request) {
                $q->where('kode_departemen', $request->departemen);
            });
            $dept = \App\Models\Departemen::where('kode_departemen', $request->departemen)->first();
            $filter_info[] = "Departemen: " . ($dept ? $dept->nama_departemen : $request->departemen);
        }

        $laporan_presensi = $query->limit(1000)->get(); // Batasi untuk performa PDF

        // Ambil data untuk PDF menggunakan trait
        $title = 'Laporan Presensi Karyawan';
        $period = $this->generatePeriodText($request->bulan, $request->tahun);
        
        $data = $this->getPDFData($title, $period);
        $data['laporan_presensi'] = $laporan_presensi;
        $data['filter_info'] = implode(' | ', $filter_info);

        $pdf = Pdf::loadView('admin.dashboard.laporan.presensi.pdf-all', $data);
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('laporan_presensi_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Cetak laporan presensi per karyawan dalam bentuk PDF
     */
    public function cetakPerKaryawan(Request $request, $nik)
    {
        // Validasi input
        $request->validate([
            'bulan' => 'required|in:01,02,03,04,05,06,07,08,09,10,11,12',
            'tahun' => 'required|digits:4',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Ambil data karyawan
        $karyawan = DB::table('karyawan')
            ->leftJoin('departemens', 'karyawan.kode_departemen', '=', 'departemens.kode_departemen')
            ->where('karyawan.nik', $nik)
            ->select('karyawan.*', 'departemens.nama_departemen')
            ->first();

        if (!$karyawan) {
            return back()->with('error', 'Karyawan tidak ditemukan');
        }

        // Ambil data presensi pada bulan & tahun
        $presensi = Presensi::where('nik', $nik)
            ->whereMonth('tanggal_presensi', $bulan)
            ->whereYear('tanggal_presensi', $tahun)
            ->orderBy('tanggal_presensi')
            ->get();

        // Hitung rekap
        $totalHadir = $presensi->count();
        $totalTerlambat = $presensi->where('jam_masuk', '>', '07:00:00')->count();

        // Ambil data untuk PDF menggunakan trait
        $title = 'Laporan Presensi Karyawan';
        $period = 'Periode: ' . strtoupper(Carbon::create()->month((int) $bulan)->translatedFormat('F')) . ' ' . $tahun;
        
        $data = $this->getPDFData($title, $period);
        $data['karyawan'] = $karyawan;
        $data['presensi'] = $presensi;
        $data['bulan'] = Carbon::create()->month((int) $bulan)->translatedFormat('F');
        $data['tahun'] = $tahun;
        $data['totalHadir'] = $totalHadir;
        $data['totalTerlambat'] = $totalTerlambat;

        // Generate PDF
        $pdf = Pdf::loadView('admin.dashboard.laporan.presensi.pdf', $data)
            ->setPaper('a4', 'portrait');

        $fileName = 'laporan_presensi_' . $nik . '_' . $bulan . '_' . $tahun . '.pdf';
        return $pdf->download($fileName);
    }

    /**
     * Cetak rekap presensi dalam bentuk PDF
     */
    public function cetakRekap(Request $request)
    {
        // Build query yang sama dengan rekap
        $query = DB::table('karyawan')
            ->leftJoin('departemens', 'karyawan.kode_departemen', '=', 'departemens.kode_departemen')
            ->leftJoin('presensi', 'karyawan.nik', '=', 'presensi.nik')
            ->select(
                'karyawan.nik',
                'karyawan.nama_lengkap',
                'departemens.nama_departemen',
                DB::raw('COUNT(CASE WHEN presensi.tanggal_presensi IS NOT NULL THEN 1 END) as total_hadir'),
                DB::raw('COUNT(CASE WHEN presensi.jam_masuk > "07:00:00" THEN 1 END) as total_terlambat')
            );

        // Terapkan filter
        if ($request->filled('bulan')) {
            $query->whereMonth('presensi.tanggal_presensi', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('presensi.tanggal_presensi', $request->tahun);
        }
        if ($request->filled('departemen')) {
            $query->where('departemens.kode_departemen', $request->departemen);
        }

        $rekap_karyawan = $query->groupBy('karyawan.nik', 'karyawan.nama_lengkap', 'departemens.nama_departemen')
            ->orderBy('karyawan.nama_lengkap')
            ->get();

        // Hitung summary
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $summary = $this->hitungSummaryRekap($rekap_karyawan, $bulan, $tahun);

        // Ambil nama departemen untuk filter
        $departemenFilter = null;
        if ($request->filled('departemen')) {
            $dept = Departemen::where('kode_departemen', $request->departemen)->first();
            $departemenFilter = $dept ? $dept->nama_departemen : 'Departemen tidak ditemukan';
        }

        // Ambil data untuk PDF menggunakan trait
        $title = 'Rekap Presensi Karyawan';
        $period = ($bulan ? Carbon::create()->month((int) $bulan)->translatedFormat('F') : 'Semua Bulan') . ' ' . ($tahun ? $tahun : date('Y'));
        
        $data = $this->getPDFData($title, $period);
        $data['rekap_karyawan'] = $rekap_karyawan;
        $data['summary'] = $summary;
        $data['departemenFilter'] = $departemenFilter;

        // Generate PDF
        $pdf = Pdf::loadView('admin.dashboard.laporan.rekap.pdf', $data)
            ->setPaper('a4', 'landscape'); // landscape untuk tabel yang lebar

        $fileName = 'rekap_presensi_' . 
                   ($bulan ? $bulan : 'semua') . '_' . 
                   ($tahun ? $tahun : date('Y')) . '_' . 
                   date('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($fileName);
    }
}
