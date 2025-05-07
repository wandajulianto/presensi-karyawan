<?php

namespace App\Http\Controllers\Admin;

use App\Models\PengajuanIzin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Ambil data total karyawan
        $totalKaryawan = DB::table('karyawan')->count();

        // Ambil tanggal hari ini, bulan dan tahun saat ini
        $today = Carbon::today();

        // Ambil rekap presensi: total kehadiran pada hari ini
        $recapPresention = DB::table('presensi')
            ->selectRaw('COUNT(nik) as totalPresence, SUM(IF(jam_masuk > "07:00", 1, 0)) as totalLate')
            ->whereDay('tanggal_presensi', $today)
            ->first();

        // Ambil rekap presensi: total izin dan sakit pada hari ini
        $recapIzin = PengajuanIzin::selectRaw('SUM(IF(status = "i", 1, 0)) as totalIzin, SUM(IF(status = "s", 1, 0)) as totalSakit')
            ->whereDay('tanggal_izin', $today)
            ->where('status_approved', 1)
            ->first();

        return view('admin.dashboard.index', [
            'recapPresention' => $recapPresention,
            'totalKaryawan' => $totalKaryawan,
            'recapIzin' => $recapIzin,
        ]);
    }
}
