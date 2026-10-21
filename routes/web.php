<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProdukController;

// Halaman frontend (home)
Route::get('/', function () {
    return view('frontend.home');
})->name('home');

// Halaman login (tampilkan form)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Halaman admin dashboard
Route::get('/admin', function () {
    $totalUsers = DB::table('users')->count();
    $latestUsers = DB::table('users')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    return view('admin.dashboard', compact('totalUsers', 'latestUsers'));
})->name('admin.dashboard');

// CRUD produk (pakai ProdukController)
Route::get('/admin/produk', [ProdukController::class, 'index'])->name('admin.produk');
Route::get('/admin/produk/create', [ProdukController::class, 'create'])->name('admin.produk.create');
Route::post('/admin/produk', [ProdukController::class, 'store'])->name('admin.produk.store');
Route::get('/admin/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('admin.produk.edit');
Route::put('/admin/produk/{produk}', [ProdukController::class, 'update'])->name('admin.produk.update');
Route::delete('/admin/produk/{produk}', [ProdukController::class, 'destroy'])->name('admin.produk.destroy');