<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PelangganController;

Route::get('/', function () {
    return view('welcome');
});

// Route Auth
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route resource untuk lapangan
Route::resource('lapangans', LapanganController::class);

// Route resource untuk jadwal
Route::resource('jadwals', JadwalController::class);

//Route resource untuk pelanggan
Route::resource('pelanggans', PelangganController::class);
