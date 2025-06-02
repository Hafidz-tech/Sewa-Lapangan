<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PembayaranController;

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

//Route untuk pemesanan
Route::get('/pemesanans/create', [PemesananController::class, 'create'])->name('pemesanans.create');
Route::post('/pemesanans', [PemesananController::class, 'store'])->name('pemesanans.store');
Route::get('/pemesanans', [PemesananController::class, 'index'])->name('pemesanans.index'); // kalau perlu daftar
Route::delete('/pemesanans/{id}', [PemesananController::class, 'destroy'])->name('pemesanans.destroy');


//route untuk pembayaran
Route::post('/pembayarans/bayar/{pemesanan}', [PembayaranController::class, 'bayar'])->name('pembayarans.bayar');

