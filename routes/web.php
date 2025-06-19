<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MonitorPresensiController;
use App\Http\Controllers\Admin\KantorController;

use App\Http\Controllers\Admin\DataMaster\KaryawanController;
use App\Http\Controllers\Admin\DataMaster\DepartemenController;

use App\Http\Controllers\Admin\Laporan\LaporanPresensiController;
use App\Http\Controllers\Admin\PengajuanIzinController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\KonfigurasiKopSuratController;

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/admin', function () {
        return view('admin.auth.login');
    })->name('login.admin');

    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    
    Route::post('/login/admin', [AuthController::class, 'loginAdmin'])->name('login.process.admin');
});

// Deployment routes (protected by environment check)
Route::middleware(['throttle:5,1'])->group(function () {
    Route::post('/artisan/migrate', function () {
        if (app()->environment('production')) {
            try {
                Artisan::call('migrate', ['--force' => true]);
                Artisan::call('config:cache');
                Artisan::call('route:cache');
                Artisan::call('view:cache');
                Artisan::call('storage:link');
                return response()->json(['status' => 'success', 'message' => 'Deployment commands executed successfully']);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
        }
        return response()->json(['status' => 'error', 'message' => 'Not in production environment'], 403);
    });
    
    Route::get('/deploy/status', function () {
        return response()->json([
            'status' => 'ok',
            'environment' => app()->environment(),
            'timestamp' => now(),
            'laravel_version' => app()->version()
        ]);
    });
});

Route::middleware('auth:user')->group(function () {
    // Admin dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.admin');
    
    // Logout
    Route::get('/admin/logout', [AuthController::class, 'logout'])->name('logout.admin');

    // Data Master Karyawan
    Route::controller(KaryawanController::class)
        ->prefix('admin/data-master/karyawan')
        ->group(function () {
            Route::get('/create', 'create')->name('data-master.karyawan.create');
            Route::get('/{nik}/edit', 'edit')->name('data-master.karyawan.edit');
            Route::put('/{nik}','update')->name('data-master.karyawan.update');
            Route::delete('/{nik}', 'delete')->name('data-master.karyawan.delete');

            Route::post('/store', 'store')->name('data-master.karyawan.store');
            Route::get('/', 'index')->name('data-master.karyawan');
        });

    // Data Master Departemen
    Route::controller(DepartemenController::class)
        ->prefix('admin/data-master/departemen')
        ->group(function () {
            Route::get('/create', 'create')->name('data-master.departemen.create');
            Route::post('/store', 'store')->name('data-master.departemen.store');
            Route::get('/{kode_departemen}/edit', 'edit')->name('data-master.departemen.edit');
            Route::put('/{kode_departemen}', 'update')->name('data-master.departemen.update');
            Route::delete('/{kode_departemen}', 'delete')->name('data-master.departemen.delete');

            Route::get('/', 'index')->name('data-master.departemen');
        });

    // Monitoring Presensi
    Route::controller(MonitorPresensiController::class)
        ->prefix('admin/monitoring-presensi')
        ->group(function () {
            Route::get('/', 'index')->name('dashboard.admin.monitoring-presensi');
            Route::get('/export-keterlambatan', 'exportKeterlambatan')->name('dashboard.admin.monitoring-presensi.export-keterlambatan');
            Route::get('/cetak-keterlambatan', 'cetakKeterlambatan')->name('dashboard.admin.monitoring-presensi.cetak-keterlambatan');
        });

    // Laporan Presensi
            Route::controller(LaporanPresensiController::class)
        ->prefix('admin/laporan/presensi')
        ->group(function () {
            Route::get('/', 'index')->name('dashboard.admin.laporan-presensi');
            Route::get('/export', 'export')->name('dashboard.admin.laporan-presensi.export');
            Route::get('/export-rekap', 'exportRekap')->name('dashboard.admin.laporan-presensi.export-rekap');
            Route::get('/cetak-rekap', 'cetakRekap')->name('dashboard.admin.laporan-presensi.cetak-rekap');
        Route::get('/cetak', 'cetakPresensi')->name('dashboard.admin.laporan-presensi.cetak');
            Route::get('/export-keterlambatan', 'exportKeterlambatan')->name('dashboard.admin.laporan-presensi.rekap');
            Route::get('/cetak/{nik}', 'cetakPerKaryawan')->name('dashboard.admin.laporan-presensi.cetak-per-karyawan');
        });

    // Konfigurasi Kantor
    Route::controller(KantorController::class)
        ->prefix('admin/konfigurasi/kantor')
        ->group(function () {
            Route::get('/', 'index')->name('admin.kantor.index');
            Route::get('/create', 'create')->name('admin.kantor.create');
            Route::post('/store', 'store')->name('admin.kantor.store');
            Route::get('/{kantor}/edit', 'edit')->name('admin.kantor.edit');
            Route::put('/{kantor}', 'update')->name('admin.kantor.update');
            Route::delete('/{kantor}', 'destroy')->name('admin.kantor.destroy');
            Route::patch('/{kantor}/set-active', 'setActive')->name('admin.kantor.set-active');
            Route::post('/geocode', 'geocode')->name('admin.kantor.geocode');
        });

    // Pengajuan Izin routes
    Route::controller(PengajuanIzinController::class)
        ->prefix('admin/pengajuan-izin')
        ->group(function () {
            Route::get('/', 'index')->name('admin.pengajuan-izin.index');
            Route::get('/create', 'create')->name('admin.pengajuan-izin.create');
            Route::get('/export/csv', 'export')->name('admin.pengajuan-izin.export');
            Route::get('/cetak/pdf', 'cetakPDF')->name('admin.pengajuan-izin.cetak-pdf');
            Route::post('/', 'store')->name('admin.pengajuan-izin.store');
            Route::get('/{pengajuanIzin}', 'show')->name('admin.pengajuan-izin.show');
            Route::get('/{pengajuanIzin}/edit', 'edit')->name('admin.pengajuan-izin.edit');
            Route::put('/{pengajuanIzin}', 'update')->name('admin.pengajuan-izin.update');
            Route::delete('/{pengajuanIzin}', 'destroy')->name('admin.pengajuan-izin.destroy');
            Route::patch('/{pengajuanIzin}/approve', 'approve')->name('admin.pengajuan-izin.approve');
            Route::patch('/{pengajuanIzin}/reject', 'reject')->name('admin.pengajuan-izin.reject');
        });

    // Admin Profile routes
    Route::controller(AdminProfileController::class)
        ->prefix('admin/profile')
        ->group(function () {
            Route::get('/', 'edit')->name('admin.profile.edit');
            Route::put('/update', 'update')->name('admin.profile.update');
        });

    // Konfigurasi Kop Surat routes
    Route::controller(KonfigurasiKopSuratController::class)
        ->prefix('admin/konfigurasi/kop-surat')
        ->group(function () {
            Route::get('/', 'index')->name('admin.konfigurasi.kop-surat.index');
            Route::put('/update', 'update')->name('admin.konfigurasi.kop-surat.update');
            Route::get('/preview', 'preview')->name('admin.konfigurasi.kop-surat.preview');
        });
});

// Route untuk user yang sudah login (authenticated)
Route::middleware('auth:karyawan')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Presensi
    Route::controller(PresensiController::class)
        ->prefix('presensi')
        ->group(function () {
            Route::get('/create', 'create')->name('presensi.create');
            Route::post('/store', 'store')->name('presensi.store');
            Route::get('/history', 'history')->name('presensi.history');
            Route::post('/history/search', 'searchHistory')->name('presensi.history.search');
            Route::get('/izin', 'izin')->name('presensi.izin');
            Route::get('/create/izin', 'createIzin')->name('presensi.create.izin');
            Route::post('/store/izin', 'storeIzin')->name('presensi.store.izin');
    });

    // Profile
    Route::controller(ProfileController::class)
        ->prefix('profile')
        ->group(function () {
            Route::get('/', 'edit')->name('profile');
            Route::put('/{nik}/update', 'update')->name('profile.update');
        });
});
