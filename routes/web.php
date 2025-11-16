<?php

use App\Http\Controllers\LaporanPenjualanController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelangganAuthController;
use App\Http\Controllers\CustomerHomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Models\Barang;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('welcome'))->name('welcome');

/* ===================== AUTH (Custom) ===================== */
Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/* ================== REGISTER PELANGGAN =================== */
Route::get('/pelanggan/register',  [PelangganAuthController::class, 'showRegisterForm'])
    ->name('pelanggan.register');
Route::post('/pelanggan/register', [PelangganAuthController::class, 'register'])
    ->name('pelanggan.register.store');

/* ===================== STAFF / ADMIN ===================== */
Route::middleware('auth:staff')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    /* ====== Halaman UI sesuai sidebar (mockup) ====== */
    Route::get('/tambah', function () {
        $barang = new Barang();
        return view('barang.tambah', compact('barang'));
    })->name('ui.tambah');

    Route::post('/barang/quick-store', [BarangController::class, 'quickStore'])
        ->name('barang.quick.store');

    Route::get('/edit', [BarangController::class, 'quickEditPage'])
        ->name('ui.edit');

    Route::post('/barang/quick-update', [BarangController::class, 'quickUpdate'])
        ->name('barang.quick.update');

    Route::get('/hapus', function () {
        $q = request('q');
        $barangs = Barang::query()
            ->when($q, function ($qq) use ($q) {
                $qq->where('id_barang', 'like', "%{$q}%")
                   ->orWhere('nama_barang', 'like', "%{$q}%");
            })
            ->orderBy('id_barang')
            ->paginate(8)
            ->withQueryString();

        return view('barang.hapus', compact('barangs', 'q'));
    })->name('ui.hapus');

    /* ====== Laporan Barang (VIEW) ====== */
    // View: resources/views/filament/pages/laporan-barang.blade.php
    Route::get('/laporan/barang', function () {
        $q = request('q');
        $periode = request('periode');
        $statusFilter = request('status');
        
        $today = \Carbon\Carbon::today();
        $warnDays = 30;
        $lowStock = 10;

        $query = Barang::query()
            ->when($q, function ($qq) use ($q) {
                $qq->where('id_barang','like',"%{$q}%")
                   ->orWhere('nama_barang','like',"%{$q}%");
            })
            ->when($periode, function ($qq) use ($periode, $today) {
                // Filter berdasarkan periode yang dipilih
                switch ($periode) {
                    case 'harian':
                        // Hari ini saja
                        $qq->whereDate('tanggal_kedaluwarsa', $today);
                        break;
                    case 'mingguan':
                        // 7 hari ke depan dari hari ini
                        $qq->whereDate('tanggal_kedaluwarsa', '>=', $today)
                           ->whereDate('tanggal_kedaluwarsa', '<=', $today->copy()->addDays(7));
                        break;
                    case 'bulanan':
                        // 30 hari ke depan dari hari ini
                        $qq->whereDate('tanggal_kedaluwarsa', '>=', $today)
                           ->whereDate('tanggal_kedaluwarsa', '<=', $today->copy()->addDays(30));
                        break;
                    case 'tahunan':
                        // 365 hari ke depan dari hari ini
                        $qq->whereDate('tanggal_kedaluwarsa', '>=', $today)
                           ->whereDate('tanggal_kedaluwarsa', '<=', $today->copy()->addDays(365));
                        break;
                }
            });

        // Filter berdasarkan status
        if ($statusFilter) {
            $query->where(function ($qq) use ($statusFilter, $today, $warnDays, $lowStock) {
                switch ($statusFilter) {
                    case 'Kadaluwarsa':
                        // Barang yang sudah kadaluwarsa (tanggal < today)
                        $qq->whereNotNull('tanggal_kedaluwarsa')
                           ->whereDate('tanggal_kedaluwarsa', '<', $today);
                        break;
                    case 'Hampir Kadaluwarsa':
                        // Barang yang akan kadaluwarsa dalam 30 hari ke depan
                        // Tapi tidak termasuk yang sudah kadaluwarsa
                        $qq->whereNotNull('tanggal_kedaluwarsa')
                           ->whereDate('tanggal_kedaluwarsa', '>=', $today)
                           ->whereDate('tanggal_kedaluwarsa', '<=', $today->copy()->addDays($warnDays));
                        break;
                    case 'Hampir Habis':
                        // Barang dengan stok <= 10
                        // Tapi tidak termasuk yang sudah kadaluwarsa atau hampir kadaluwarsa
                        // (karena prioritas status: Kadaluwarsa > Hampir Kadaluwarsa > Hampir Habis)
                        $qq->where('stok_barang', '<=', $lowStock)
                           ->where(function ($q) use ($today, $warnDays) {
                               // Tidak ada tanggal kadaluwarsa ATAU tanggal > today+30 (tidak hampir kadaluwarsa)
                               $q->whereNull('tanggal_kedaluwarsa')
                                 ->orWhereDate('tanggal_kedaluwarsa', '>', $today->copy()->addDays($warnDays));
                           });
                        break;
                    case 'Aman':
                        // Barang yang tidak kadaluwarsa, tidak hampir kadaluwarsa, dan stok > 10
                        $qq->where('stok_barang', '>', $lowStock)
                           ->where(function ($q) use ($today, $warnDays) {
                               $q->whereNull('tanggal_kedaluwarsa')
                                 ->orWhereDate('tanggal_kedaluwarsa', '>', $today->copy()->addDays($warnDays));
                           });
                        break;
                }
            });
        }

        $barangs = $query->orderBy('tanggal_kedaluwarsa')
            ->paginate(8)
            ->withQueryString();

        return view('filament.pages.laporan-barang', compact('barangs', 'q'));
    })->name('ui.laporan-barang');

    // Alias opsional: /laporan-barang -> /laporan/barang
    Route::redirect('/laporan-barang', '/laporan/barang')->name('ui.laporan-barang.alias');

    /* ====== Laporan Penjualan ====== */
    // View: resources/views/filament/pages/laporan-penjualan.blade.php
    Route::get('/laporan-penjualan', [LaporanPenjualanController::class, 'index'])
        ->name('ui.laporan-penjualan');

    // ALIAS: /laporan/penjualan -> /laporan-penjualan
    Route::redirect('/laporan/penjualan', '/laporan-penjualan')
        ->name('ui.laporan-penjualan.alias');

    /* ====== ROUTE RESOURCE (CRUD DB) ====== */
    Route::resource('barang', BarangController::class)
        ->parameters(['barang' => 'barang'])
        ->except(['show']);
});

/* ===================== PELANGGAN ========================= */
Route::middleware('auth:pelanggan')->group(function () {
    Route::get('/home', [CustomerHomeController::class, 'index'])->name('customer.home');
    Route::get('/home/search', [CustomerHomeController::class, 'search'])->name('customer.search');

    // CART khusus pelanggan
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id_barang}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

/* ===================== PROFIL (opsional) ================= */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// require __DIR__ . '/auth.php';
