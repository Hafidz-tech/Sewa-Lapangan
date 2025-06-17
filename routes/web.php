<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PembayaranController; //mengimpor agar bisa digunakan dalam route

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
Route::resource('lapangans', LapanganController::class); //untuk function CRUD pada lapangan

// Route resource untuk jadwal
Route::resource('jadwals', JadwalController::class); //resource membuat sekaligus route untuk function dicontroller bawaan laravel

//Route resource untuk pelanggan
Route::resource('pelanggans', PelangganController::class);

//Route untuk pemesanan
Route::resource('pemesanans', PemesananController::class); 

// Route untuk pembayaran
Route::post('/pembayarans/bayar/{pemesanan}', [PembayaranController::class, 'bayar'])->name('pembayarans.bayar');
      //mengirim data
//Route untuk setiap lapangan memiliki jadwal masing masing
Route::get('/get-jadwal/{lapangan_id}', [PemesananController::class, 'getJadwal'])->name('get-jadwal');
      //mengambil data