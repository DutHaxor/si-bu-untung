<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Ubah data lama dari 'dikirim' ke 'terkirim' sebelum mengubah enum
        DB::table('transaksi')
            ->where('status_transaksi', 'dikirim')
            ->update(['status_transaksi' => 'terkirim']);

        // Tambahkan field bukti_pengiriman
        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('bukti_pengiriman', 255)->nullable()->after('status_transaksi');
        });

        // Ubah enum status_transaksi untuk menambahkan 'dalam_pengiriman' dan 'terkirim'
        // Menggunakan DB::statement karena Laravel tidak mendukung modifikasi enum langsung
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN status_transaksi ENUM('pending', 'dibayar', 'dalam_pengiriman', 'terkirim') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Kembalikan enum ke versi sebelumnya
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN status_transaksi ENUM('pending', 'dibayar', 'dikirim') NOT NULL DEFAULT 'pending'");

        // Hapus field bukti_pengiriman
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn('bukti_pengiriman');
        });
    }
};
