<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ReceiptController;
use App\Services\ReceiptPrinter;
use Illuminate\Http\Request;

//login sementara untuk pengujian
Route::get('/masuk-test', function () {
    Auth::loginUsingId(1);
    return redirect()->route('admin.produk');
});


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
Route::get('/admin/produk/invoice', [ProdukController::class, 'invoice'])->name('admin.produk.invoice');
Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
Route::post('orders', [OrderController::class, 'store'])->middleware('auth')->name('orders.store');
Route::get('/print-receipt', [ReceiptController::class, 'print']);
Route::get('/struk', function () {
    return view('struk.form');
})->name('struk.form');
Route::post('/struk', function (Request $request, ReceiptPrinter $printer) {
    $request->validate([
        'items'         => 'required|array|min:1',
        'items.*.nama'  => 'required|string|max:100',
        'items.*.qty'   => 'required|integer|min:1',
        'items.*.harga' => 'required|integer|min:0',
        'diskon'        => 'nullable|numeric|min:0|max:100',
    ]);

    $items = array_values($request->input('items'));

    $subtotal = 0;
    foreach ($items as $item) {
        $subtotal += $item['qty'] * $item['harga'];
    }
    $persen = (float) $request->input('diskon', 0);

    $data = [
        'toko'          => 'Kopi Kita',
        'alamat'        => 'Jl. Mawar No. 123, Jember',
        'kode'          => 'INV-' . now('Asia/Jakarta')->format('ymdHis'),
        'tanggal'       => now('Asia/Jakarta')->format('d/m/Y H:i'),
        'items'         => $items,
        'diskon'        => (int) round($subtotal * $persen / 100),
        'diskon_persen' => $persen,
    ];

    $lokasi = $printer->cetak($data);

    return view('struk.form', [
        'baris'  => $printer->buatBaris($data),
        'lokasi' => $lokasi,
    ]);
})->name('struk.cetak');