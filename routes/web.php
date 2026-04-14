<?php

use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TagHargaController;
use App\Http\Controllers\JspageController;
use App\Http\Controllers\AjaxAxiosController;
use App\Http\Controllers\Customer\DashboardCustomer;
use App\Http\Controllers\OrderController;

Route::get('/', [DashboardCustomer::class, 'index'])->name('customer.dashboard');

Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [DashboardCustomer::class, 'index'])->name('dashboard');
    Route::get('/menu/{vendorId}', [DashboardCustomer::class, 'showMenu'])->name('menu');
    Route::get('/menu/{vendorId}/items', [DashboardCustomer::class, 'getMenuByVendor'])->name('menu.items');
    Route::post('/order', [OrderController::class, 'store'])->name('store');
});

Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/pesanan/{pesanan}', [OrderController::class, 'showPayment'])->name('show');
});

Route::middleware(['guest'])->group(function () {
    Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::get('auth/google', [GoogleController::class, 'redirect']);
    Route::get('auth/google/callback', [GoogleController::class, 'callback']);
    Route::get('otp', [App\Http\Controllers\OTPController::class, 'show'])->name('otp.show');
    Route::post('otp/verify', [App\Http\Controllers\OTPController::class, 'verify'])->name('otp.verify');
    Route::post('otp/resend', [App\Http\Controllers\OTPController::class, 'resend'])->name('otp.resend');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::get('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/', [App\Http\Controllers\KategoriController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\KategoriController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\KategoriController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [App\Http\Controllers\KategoriController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\KategoriController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\KategoriController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('buku')->name('buku.')->group(function () {
        Route::get('/', [App\Http\Controllers\BukuController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\BukuController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\BukuController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [App\Http\Controllers\BukuController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\BukuController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\BukuController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('Cetak')->name('cetak.')->group(function () {
        Route::get('/Sertifikat', [App\Http\Controllers\CetakController::class, 'cetakSertif'])->name('sertifikat');
        Route::get('/Sertifikat/download', [App\Http\Controllers\CetakController::class, 'downloadSertif'])->name('sertifikat.download');
        Route::get('/Undangan', [App\Http\Controllers\CetakController::class, 'cetakUndangan'])->name('undangan');
        Route::get('/Undangan/download', [App\Http\Controllers\CetakController::class, 'downloadUndangan'])->name('undangan.download');
    });

    Route::prefix('tag-harga')->name('tag-harga.')->group(function () {
        Route::get('/', [TagHargaController::class, 'index'])->name('index');
         Route::post('/print', [TagHargaController::class, 'print'])->name('print');
        Route::get('/create', [TagHargaController::class, 'create'])->name('create');
        Route::post('/store', [TagHargaController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [TagHargaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TagHargaController::class, 'update'])->name('update');
        Route::delete('/{id}', [TagHargaController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('jspage')->name('jspage.')->group(function () {
        Route::get('/', [JspageController::class, 'index'])->name('index');
        Route::get('/datatables', [JspageController::class, 'datatables'])->name('datatables');
        Route::get('/kota', [JspageController::class, 'kota'])->name('kota');
    });

    Route::prefix('ajax-axios')->name('ajax-axios.')->group(function () {
        Route::get('/', [AjaxAxiosController::class, 'ajax'])->name('ajax');
        Route::get('/axios', [AjaxAxiosController::class, 'axios'])->name('axios');
        Route::get('/regencies', [AjaxAxiosController::class, 'regencies'])->name('regencies');
        Route::get('/districts', [AjaxAxiosController::class, 'districts'])->name('districts');
        Route::get('/villages', [AjaxAxiosController::class, 'villages'])->name('villages');
    });
    
});

