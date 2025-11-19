# Setup Midtrans Payment Gateway (Sandbox)

## Langkah-langkah Setup

### 1. Install Dependencies

Jalankan perintah berikut untuk menginstall Midtrans SDK:

```bash
composer install
```

Atau jika sudah pernah install sebelumnya:

```bash
composer update midtrans/midtrans-php
```

### 2. Daftar Akun Midtrans Sandbox

1. Kunjungi https://dashboard.sandbox.midtrans.com/register
2. Daftar akun baru atau login jika sudah punya
3. Setelah login, buka menu **Settings** > **Access Keys**

### 3. Setup Environment Variables

Tambahkan konfigurasi berikut ke file `.env` Anda:

```env
# Midtrans Configuration (Sandbox)
MIDTRANS_SERVER_KEY=your_server_key_here
MIDTRANS_CLIENT_KEY=your_client_key_here
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

**Cara mendapatkan Server Key dan Client Key:**

-   Login ke https://dashboard.sandbox.midtrans.com/
-   Buka menu **Settings** > **Access Keys**
-   Copy **Server Key** dan **Client Key** (Sandbox)
-   Paste ke file `.env` Anda

### 4. Clear Config Cache (Opsional)

Jika sudah mengubah file `.env`, jalankan:

```bash
php artisan config:clear
php artisan cache:clear
```

### 5. Test Pembayaran Sandbox

Setelah setup selesai, Anda bisa test pembayaran dengan menggunakan kartu kredit atau metode pembayaran sandbox berikut:

#### Kartu Kredit Test:

-   **Card Number**: `4811 1111 1111 1114`
-   **CVV**: `123`
-   **Expiry**: Bulan/tahun apa saja di masa depan (contoh: `12/25`)
-   **OTP**: `112233`

#### QRIS Test:

-   Gunakan aplikasi e-wallet atau bank yang mendukung QRIS
-   Scan QRIS yang muncul di halaman pembayaran
-   Gunakan kode sandbox untuk testing

#### E-Wallet Test:

-   GoPay: Gunakan nomor telepon sandbox
-   OVO: Gunakan nomor telepon sandbox
-   LinkAja: Gunakan nomor telepon sandbox

### 6. Webhook/Notification URL

Untuk menerima notifikasi pembayaran secara otomatis, tambahkan URL berikut di dashboard Midtrans:

**URL Notification:**

```
https://yourdomain.com/checkout/payment/notification
```

**Cara Setup:**

1. Login ke https://dashboard.sandbox.midtrans.com/
2. Buka menu **Settings** > **Configuration**
3. Scroll ke **Payment Notification URL**
4. Masukkan URL di atas
5. Simpan

### 7. Cara Kerja Flow Pembayaran

1. User klik **"Bayar Sekarang"** di halaman checkout
2. Sistem membuat transaksi dengan status `pending`
3. User diarahkan ke halaman pembayaran dengan Midtrans Snap
4. User memilih metode pembayaran dan menyelesaikan pembayaran
5. Midtrans mengirim callback ke sistem
6. Sistem update status transaksi menjadi `dibayar` jika pembayaran berhasil
7. User diarahkan ke halaman sukses

### Catatan Penting

-   **Sandbox Mode**: Pastikan `MIDTRANS_IS_PRODUCTION=false` untuk testing
-   **Production Mode**: Ubah ke `MIDTRANS_IS_PRODUCTION=true` saat go live
-   **Server Key**: Jangan pernah commit Server Key ke repository public
-   **Notification URL**: Pastikan URL dapat diakses dari internet (jika production)

### Troubleshooting

**Error: "Snap token not available"**

-   Pastikan Server Key dan Client Key sudah benar di `.env`
-   Pastikan Midtrans SDK sudah terinstall
-   Check log error untuk detail lebih lanjut

**Payment tidak terupdate otomatis**

-   Pastikan Notification URL sudah di-set di dashboard Midtrans
-   Pastikan route `/checkout/payment/notification` dapat diakses
-   Check log untuk melihat apakah notification diterima

**Snap tidak muncul di halaman**

-   Pastikan Client Key sudah benar
-   Pastikan script Midtrans Snap JS sudah terload
-   Check console browser untuk error JavaScript

### Referensi

-   [Dokumentasi Midtrans](https://docs.midtrans.com/)
-   [Midtrans Sandbox Dashboard](https://dashboard.sandbox.midtrans.com/)
-   [Midtrans Testing Cards](https://docs.midtrans.com/docs/testing-pembayaran)
