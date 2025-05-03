<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;

Route::middleware(['guest:karyawan'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login'); // Ini tetap route GET '/' untuk tampilan

    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

Route::middleware('auth:karyawan')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/logout', [AuthController::class, 'logout']);

    // Presensi
    Route::get('/presention/create', [PresensiController::class, 'create'])->name('presention.create');
    Route::post('/presention/store', [PresensiController::class, 'store'])->name('presention.store');
});
