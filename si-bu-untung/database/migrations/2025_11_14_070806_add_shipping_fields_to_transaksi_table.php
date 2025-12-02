<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('nama_penerima', 150)->nullable()->after('id_pelanggan');
            $table->string('telepon_penerima', 30)->nullable()->after('nama_penerima');
            $table->text('alamat_pengiriman')->nullable()->after('telepon_penerima');
            $table->date('tanggal_pengiriman')->nullable()->after('tanggal_transaksi');
            $table->string('waktu_pengiriman', 20)->nullable()->after('tanggal_pengiriman');
            $table->text('catatan')->nullable()->after('status_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn([
                'nama_penerima',
                'telepon_penerima',
                'alamat_pengiriman',
                'tanggal_pengiriman',
                'waktu_pengiriman',
                'catatan'
            ]);
        });
    }
};
