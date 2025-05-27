<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Departemen;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPresensiController extends Controller
{
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

        $callback = function() use ($laporan_presensi) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
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

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Halaman rekap presensi (untuk route kedua)
     */
    public function exportKeterlambatan()
    {
        return view('admin.dashboard.laporan.rekap.index');
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

        $data = [
            'karyawan' => $karyawan,
            'presensi' => $presensi,
            'bulan' => Carbon::create()->month((int) $bulan)->translatedFormat('F'),
            'tahun' => $tahun,
            'totalHadir' => $totalHadir,
            'totalTerlambat' => $totalTerlambat,
            'instansi' => config('app.name', 'Nama Instansi'),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('admin.dashboard.laporan.presensi.pdf', $data)
            ->setPaper('a4', 'portrait');

        $fileName = 'laporan_presensi_' . $nik . '_' . $bulan . '_' . $tahun . '.pdf';
        return $pdf->download($fileName);
    }
}
