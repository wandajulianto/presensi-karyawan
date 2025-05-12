<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DataMaster\KaryawanController;
use App\Http\Controllers\Admin\DataMaster\DepartemenController;

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

Route::middleware('auth:user')->group(function () {
    // Admin dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.admin');

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
