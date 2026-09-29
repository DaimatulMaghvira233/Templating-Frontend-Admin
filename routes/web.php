<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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

// Halaman admin produk
// Tampilkan semua produk
Route::get('/admin/produk', function () {
    $produk = DB::table('produk')->get();
    return view('admin.produk', compact('produk'));
})->name('admin.produk');

// Form tambah produk
Route::get('/admin/produk/create', function () {
    return view('admin.produk-create');
})->name('admin.produk.create');

// Simpan produk baru
Route::post('/admin/produk', function (Illuminate\Http\Request $request) {
    $request->validate([
        'nama_produk' => 'required|min:3',
        'deskripsi' => 'nullable',
        'harga' => 'required|numeric',
        'stok' => 'required|integer',
    ]);

    DB::table('produk')->insert([
        'nama_produk' => $request->nama_produk,
        'deskripsi' => $request->deskripsi,
        'harga' => $request->harga,
        'stok' => $request->stok,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('admin.produk')->with('success', 'Produk berhasil ditambahkan');
})->name('admin.produk.store');

// Form edit produk
Route::get('/admin/produk/{id}/edit', function ($id) {
    $produk = DB::table('produk')->where('id', $id)->first();
    return view('admin.produk-edit', compact('produk'));
})->name('admin.produk.edit');

// Update produk
Route::put('/admin/produk/{id}', function (Illuminate\Http\Request $request, $id) {
    $request->validate([
        'nama_produk' => 'required|min:3',
        'harga' => 'required|numeric',
        'stok' => 'required|integer',
    ]);

    DB::table('produk')->where('id', $id)->update([
        'nama_produk' => $request->nama_produk,
        'deskripsi' => $request->deskripsi,
        'harga' => $request->harga,
        'stok' => $request->stok,
        'updated_at' => now(),
    ]);

    return redirect()->route('admin.produk')->with('success', 'Produk berhasil diupdate');
})->name('admin.produk.update');

// Hapus produk
Route::delete('/admin/produk/{id}', function ($id) {
    DB::table('produk')->where('id', $id)->delete();
    return redirect()->route('admin.produk')->with('success', 'Produk berhasil dihapus');
})->name('admin.produk.destroy');