<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard untuk karyawan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil user yang sedang login via guard 'karyawan'
        $user = Auth::guard('karyawan')->user();

        // Ambil data user yang dibutuhkan
        $fullName = $user->nama_lengkap;
        $role = $user->jabatan;
        $nik = $user->nik;
        $foto = $user->foto;

        // Ambil tanggal hari ini, bulan dan tahun saat ini
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $monthName = Carbon::now()->translatedFormat('F');

        // Ambil data presensi hari ini
        $todayPresence = DB::table('presensi')
            ->where('nik', $nik)
            ->whereDate('tanggal_presensi', $today)
            ->first();

        // Ambil histori presensi untuk bulan ini
        $monthlyHistory = DB::table('presensi')
            ->where('nik', $nik)
            ->whereMonth('tanggal_presensi', $currentMonth)
            ->whereYear('tanggal_presensi', $currentYear)
            ->orderBy('tanggal_presensi')
            ->get();

        // Ambil rekap presensi: total kehadiran dan total keterlambatan (jam_masuk > 07:00)
        $recapPresention = DB::table('presensi')
            ->selectRaw('COUNT(nik) as totalPresence, SUM(IF(jam_masuk > "07:00", 1, 0)) as totalLate')
            ->where('nik', $nik)
            ->whereMonth('tanggal_presensi', $currentMonth)
            ->whereYear('tanggal_presensi', $currentYear)
            ->first();

        // Kirim semua data ke tampilan dashboard
        return view('dashboard.dashboard', [
            'fullName' => $fullName,
            'role' => $role,
            'foto' => $foto,
            'todayPresention' => $todayPresence,
            'monthlyHistory' => $monthlyHistory,
            'monthName' => $monthName,
            'currentYear' => $currentYear,
            'recapPresention' => $recapPresention,
        ]);
    }
}
