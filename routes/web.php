<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DataMaster\KaryawanController;

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

    Route::get('/admin/data-master/karyawan', [KaryawanController::class, 'index'])->name('data-master.karyawan');
    Route::post('/admin/data-master/karyawan/store', [KaryawanController::class, 'store'])->name('data-master.karyawan.store');
    Route::get('/admin/data-master/karyawan/create', [KaryawanController::class, 'create'])->name('data-master.karyawan.create');
    Route::get('/admin/data-master/karyawan/{nik}/edit', [KaryawanController::class, 'edit'])->name('data-master.karyawan.edit');
    Route::put('/admin/data-master/karyawan/{nik}', [KaryawanController::class, 'update'])->name('data-master.karyawan.update');
    Route::delete('/admin/data-master/karyawan/{nik}', [KaryawanController::class, 'delete'])->name('data-master.karyawan.delete');
});

// Route untuk user yang sudah login (authenticated)
Route::middleware('auth:karyawan')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Presensi
    Route::prefix('presensi')->controller(PresensiController::class)->group(function () {
        Route::get('/create', 'create')->name('presensi.create');
        Route::post('/store', 'store')->name('presensi.store');
        Route::get('/history', 'history')->name('presensi.history');
        Route::post('/history/search', 'searchHistory')->name('presensi.history.search');
        Route::get('/izin', 'izin')->name('presensi.izin');
        Route::get('/create/izin', 'createIzin')->name('presensi.create.izin');
        Route::post('/store/izin', 'storeIzin')->name('presensi.store.izin');
    });

    // Profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile');
        Route::put('/profile/{nik}/update', 'update')->name('profile.update');
    });
});
