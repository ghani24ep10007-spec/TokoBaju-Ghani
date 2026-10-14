<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MahasiswaController;

/*
|--------------------------------------------------------------------------
| Web Routes - Toko Baju Ghani & Praktikum Web
|--------------------------------------------------------------------------
*/

// Halaman Utama: Katalog Toko Baju Ghani
Route::get('/', [ProductController::class, 'index'])->name('toko.index');
Route::post('/toko/produk', [ProductController::class, 'store'])->name('toko.store');
Route::delete('/toko/produk/{id}', [ProductController::class, 'destroy'])->name('toko.destroy');

// Modul Mahasiswa (Praktikum Pemrograman Web)
Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');

// Health Check Route for Docker & CI/CD
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'app' => config('app.name'),
        'environment' => config('app.env'),
        'timestamp' => now()->toIso8601String()
    ]);
});
