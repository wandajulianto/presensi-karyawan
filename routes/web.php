<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;

// Route untuk user yang belum login (guest)
Route::middleware(['guest:karyawan'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login'); // Form login

    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

// Route untuk user yang sudah login (authenticated)
Route::middleware('auth:karyawan')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout - menggunakan POST sesuai best practice
    // Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Presensi
    Route::prefix('presensi')->controller(PresensiController::class)->group(function () {
        Route::get('/create', 'create')->name('presensi.create');
        Route::post('/store', 'store')->name('presensi.store');
        Route::get('/history', 'history')->name('presensi.history');
        Route::post('/history/search', 'searchHistory')->name('presensi.history.search');
    });

    // Profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile');
        Route::put('/profile/{nik}/update', 'update')->name('profile.update');
    });
});
