<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelangganAuthController;
use App\Http\Controllers\CustomerHomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerTransaksiController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\PengantaranController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\DashboardController;
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
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    /* ====== Halaman UI sesuai sidebar (mockup) ====== */
    // View: resources/views/barang/tambah.blade.php
    Route::get('/tambah', [BarangController::class, 'quickAddPage'])->name('ui.tambah');

    Route::post('/barang/quick-store', [BarangController::class, 'quickStore'])
        ->name('barang.quick.store');

    // View: resources/views/barang/edit-page.blade.php
    Route::get('/edit', [BarangController::class, 'quickEditPage'])
        ->name('ui.edit');

    Route::post('/barang/quick-update', [BarangController::class, 'quickUpdate'])
        ->name('barang.quick.update');

    // View: resources/views/barang/hapus.blade.php
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

    /* ====== ROUTE RESOURCE (CRUD DB) ====== */
    Route::resource('barang', BarangController::class)
        // Pastikan getRouteKeyName() di model Barang mengembalikan 'id_barang' jika ingin binding by id_barang
        ->parameters(['barang' => 'barang'])
        ->except(['show']);

    /* ====== AJAX ROUTES untuk Barang ====== */
    Route::get('/barang/next-id', [BarangController::class, 'nextId'])->name('barang.next-id');
    Route::get('/barang/find', [BarangController::class, 'findById'])->name('barang.find');

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

    /* ====== PENGANTARAN ====== */
    // Hanya karyawan yang bisa akses pengantaran
    Route::middleware('role:karyawan')->group(function () {
        Route::get('/pengantaran', [PengantaranController::class, 'index'])->name('pengantaran.index');
        Route::post('/pengantaran/{id_transaksi}/ambil', [PengantaranController::class, 'ambilPesanan'])->name('pengantaran.ambil');
        Route::post('/pengantaran/{id_transaksi}/upload-bukti', [PengantaranController::class, 'uploadBukti'])->name('pengantaran.upload-bukti');
    });

    /* ====== Laporan Penjualan ====== */
    // Hanya owner yang bisa akses laporan penjualan
    Route::middleware('role:owner')->group(function () {
        Route::get('/laporan-penjualan', [LaporanPenjualanController::class, 'index'])
            ->name('ui.laporan-penjualan');
        
        // ALIAS: /laporan/penjualan -> /laporan-penjualan
        Route::redirect('/laporan/penjualan', '/laporan-penjualan')
            ->name('ui.laporan-penjualan.alias');

        // Staff Management
        Route::get('/staff/create', [App\Http\Controllers\StaffController::class, 'create'])->name('staff.create');
        Route::post('/staff', [App\Http\Controllers\StaffController::class, 'store'])->name('staff.store');
    });
});

/* ===================== PELANGGAN ========================= */
Route::middleware('auth:pelanggan')->group(function () {
    // Home
    Route::get('/home', [CustomerHomeController::class, 'index'])->name('customer.home');
    Route::get('/home/search', [CustomerHomeController::class, 'search'])->name('customer.search');

    // CART khusus pelanggan
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id_barang}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Checkout
    Route::match(['get', 'post'], '/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/payment/{id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::get('/checkout/payment/{id}/status', [CheckoutController::class, 'checkPaymentStatus'])->name('checkout.payment.status');
    Route::get('/checkout/payment/callback', [CheckoutController::class, 'paymentCallback'])->name('checkout.payment.callback');
    Route::post('/checkout/payment/notification', [CheckoutController::class, 'paymentNotification'])->name('checkout.payment.notification');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/payment/success', [CheckoutController::class, 'paymentSuccess'])->name('payment.success');

    // Transaksi pelanggan
    Route::get('/transaksi', [CustomerTransaksiController::class, 'index'])->name('customer.transaksi');
    Route::get('/transaksi/{id_transaksi}', [CustomerTransaksiController::class, 'show'])->name('customer.transaksi.show');

    // Profil pelanggan
    Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('customer.profile.edit');
    Route::patch('/profile', [CustomerProfileController::class, 'update'])->name('customer.profile.update');

    // Alamat pelanggan (API)
    Route::get('/alamat', [AlamatController::class, 'index'])->name('alamat.index');
    Route::post('/alamat', [AlamatController::class, 'store'])->name('alamat.store');
    Route::put('/alamat/{id}', [AlamatController::class, 'update'])->name('alamat.update');
    Route::delete('/alamat/{id}', [AlamatController::class, 'destroy'])->name('alamat.destroy');
});

// require __DIR__ . '/auth.php'; // tetap dimatikan agar tidak bentrok dengan /login custom
