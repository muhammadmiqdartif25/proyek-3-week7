<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BarangController::class, 'index'])->name('barang.index');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'form'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang', [KeranjangController::class, 'store'])->name('keranjang.store');
    Route::patch('/keranjang', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');

    Route::get('/checkout', [CheckoutController::class, 'form'])->name('checkout.form');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
});
