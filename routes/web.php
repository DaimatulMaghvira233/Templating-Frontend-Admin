<?php

use Illuminate\Support\Facades\Route;

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
    return view('admin.dashboard');
})->name('admin.dashboard');