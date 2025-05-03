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
        // Ambil tanggal hari ini menggunakan Carbon
        $today = Carbon::today();

        // Ambil bulan dan tahun sekarang
        $currentMonth = $today->month;
        $currentYear = $today->year;

        // Ambil NIK karyawan yang sedang login
        $nik = Auth::guard('karyawan')->user()->nik;

        // Ambil data presensi untuk hari ini
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

        // Tampilkan tampilan dashboard dengan data yang dibutuhkan
        return view('dashboard.dashboard', [
            'todayPresention' => $todayPresence,
            'monthlyHistory' => $monthlyHistory
        ]);
    }
}
