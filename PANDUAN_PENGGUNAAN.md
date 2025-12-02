# 📦 Panduan Penggunaan Sistem Pengantaran

## 🔐 Cara Login ke Admin Panel (Filament)

### URL Admin Panel

```
http://localhost/Toko-Bu-Untung/admin
```

atau

```
http://your-domain/admin
```

### Kredensial Login

-   **Username/Email**: Gunakan username atau email dari tabel `staff`
-   **Password**: Password yang sudah di-hash di database
-   **Role**: Harus memiliki role `owner`, `manager`, atau `karyawan`

### Cara Login

1. Buka browser dan akses URL: `http://localhost/Toko-Bu-Untung/admin`
2. Filament akan menampilkan halaman login otomatis
3. Masukkan **username** atau **email** staff
4. Masukkan **password**
5. Klik **Login**

> **Catatan**: Jika belum ada data staff, buat dulu melalui database atau seeder.

---

## 🚚 Cara Menggunakan Fitur Pengantaran

### Langkah 1: Akses Halaman Pengantaran

1. Setelah login ke admin panel, di sidebar kiri akan ada menu **"Pengantaran"** dengan icon truk 🚚
2. Klik menu **"Pengantaran"**

### Langkah 2: Melihat Daftar Pesanan

Halaman Pengantaran menampilkan:

-   Pesanan dengan status **"Dibayar"** (siap untuk diantarkan)
-   Pesanan dengan status **"Dalam Pengiriman"** (sedang dalam proses)

Kolom yang ditampilkan:

-   **ID Pesanan**: Nomor transaksi
-   **Tanggal**: Tanggal transaksi
-   **Pelanggan**: Nama pelanggan
-   **Nama Penerima**: Nama orang yang akan menerima
-   **Alamat**: Alamat pengiriman
-   **Status**: Status pesanan saat ini
-   **Staff Pengantar**: Nama staff yang mengambil pesanan (jika sudah diambil)
-   **Total**: Total harga pesanan

### Langkah 3: Mengambil Pesanan untuk Dikirim

1. Cari pesanan dengan status **"Dibayar"** di tabel
2. Klik tombol **"Ambil Pesanan"** (warna hijau) di kolom Actions
3. Konfirmasi dengan klik **"Ambil Pesanan"** di popup
4. Status pesanan akan berubah menjadi **"Dalam Pengiriman"**
5. Nama staff Anda akan muncul di kolom "Staff Pengantar"

> **Catatan**: Hanya pesanan dengan status "Dibayar" yang bisa diambil.

### Langkah 4: Upload Bukti Pengiriman

Setelah sampai di lokasi pengiriman:

1. Cari pesanan yang Anda ambil (status "Dalam Pengiriman")
2. Klik tombol **"Upload Bukti"** (warna biru) di kolom Actions
3. Upload foto bukti pengiriman:
    - Klik **"Pilih File"** atau drag & drop foto
    - Foto akan tersimpan di folder `storage/app/public/bukti-pengiriman`
    - Maksimal ukuran file: 5MB
    - Format yang didukung: JPG, PNG, dll
4. Klik **"Upload Bukti"** untuk menyimpan
5. Status pesanan akan otomatis berubah menjadi **"Terkirim"**

> **Catatan**:
>
> -   Hanya staff yang mengambil pesanan yang bisa upload bukti
> -   Foto bukti akan tersimpan dan bisa dilihat kembali

---

## 📱 Di Sisi Customer

### Melihat Status Pesanan

1. Customer login ke akun mereka
2. Buka menu **"Transaksi Saya"**
3. Status pesanan akan menampilkan:
    - **Pending**: Menunggu pembayaran
    - **Dibayar**: Sudah dibayar, menunggu pengantaran
    - **Dalam Pengiriman**: Sedang dalam proses pengantaran
    - **Terkirim**: Sudah sampai dan selesai

---

## 🔧 Setup Awal (Jika Belum)

### 1. Jalankan Migration

```bash
php artisan migrate
```

### 2. Buat Storage Link (untuk upload foto)

```bash
php artisan storage:link
```

### 3. Pastikan Folder Storage Writable

Pastikan folder `storage/app/public` bisa ditulis (chmod 775 atau 777)

### 4. Buat Data Staff (Jika Belum Ada)

Anda bisa membuat staff melalui:

-   Database langsung
-   Seeder
-   Atau melalui Filament Resource (jika sudah dikonfigurasi)

Contoh membuat staff via tinker:

```bash
php artisan tinker
```

```php
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

Staff::create([
    'id_staff' => 'STF001',
    'username' => 'karyawan1',
    'email' => 'karyawan1@example.com',
    'password' => Hash::make('password123'),
    'role' => 'karyawan'
]);
```

---

## ⚠️ Troubleshooting

### Tidak Bisa Login ke Admin Panel

-   Pastikan staff memiliki role: `owner`, `manager`, atau `karyawan`
-   Cek di database apakah password sudah di-hash dengan benar
-   Pastikan guard 'staff' sudah dikonfigurasi di `config/auth.php`

### Foto Tidak Bisa Diupload

-   Pastikan sudah menjalankan `php artisan storage:link`
-   Cek permission folder `storage/app/public`
-   Pastikan disk 'public' sudah dikonfigurasi di `config/filesystems.php`

### Halaman Pengantaran Tidak Muncul

-   Pastikan sudah menjalankan `php artisan migrate`
-   Clear cache: `php artisan config:clear` dan `php artisan cache:clear`
-   Refresh halaman admin panel

---

## 📝 Catatan Penting

1. **Status Flow**:

    - `pending` → `dibayar` → `dalam_pengiriman` → `terkirim`

2. **Staff Assignment**:

    - Setiap pesanan hanya bisa diambil oleh 1 staff
    - Staff yang mengambil pesanan harus yang upload bukti

3. **Foto Bukti**:

    - Foto disimpan di `storage/app/public/bukti-pengiriman/`
    - Bisa diakses via URL: `http://your-domain/storage/bukti-pengiriman/nama-file.jpg`

4. **Filter**:
    - Di halaman Pengantaran, Anda bisa filter berdasarkan status
    - Default menampilkan pesanan dengan status "Dibayar"

---

## 🎯 Quick Start

1. **Login**: `http://localhost/Toko-Bu-Untung/admin`
2. **Klik Menu**: "Pengantaran" di sidebar
3. **Ambil Pesanan**: Klik "Ambil Pesanan" pada pesanan yang ingin dikirim
4. **Upload Bukti**: Setelah sampai, klik "Upload Bukti" dan upload foto
5. **Selesai**: Status otomatis menjadi "Terkirim"

---

**Selamat menggunakan sistem pengantaran! 🚚✨**
