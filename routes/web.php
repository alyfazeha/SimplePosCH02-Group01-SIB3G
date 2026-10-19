<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Pengalihan halaman utama:
// Jika sudah login akan diarahkan ke /pos, jika belum akan diarahkan ke halaman /welcome
Route::get('/', function () {
    return Auth::check() ? redirect()->route('pos.create') : redirect()->route('welcome');
});

// Route khusus Tamu (Guest / Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/welcome', function () {
        return view('welcome');
    })->name('welcome');

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

// Route Logout
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Route khusus Pengguna Terautentikasi (Sudah Login)
Route::middleware('auth')->group(function () {
    // Halaman Kasir / POS (Bisa diakses oleh semua pengguna yang sudah login)
    Route::get('/pos', [TransactionController::class, 'create'])->name('pos.create');
    Route::post('/pos', [TransactionController::class, 'store'])->name('transactions.store');

    // Halaman Transaksi (Dapat diakses oleh peran admin dan manager)
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    });

    // Halaman Produk dan Kategori (HANYA dapat diakses oleh peran admin)
    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('products', ProductController::class)->except(['show', 'destroy']);
    });
});
