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
        Schema::create('alamat_pelanggan', function (Blueprint $table) {
            $table->id('id_alamat');
            $table->string('id_pelanggan', 20);
            $table->string('label', 50)->default('Alamat'); // Rumah, Kos, Kantor, dll
            $table->string('nama_penerima', 100);
            $table->string('telepon', 15);
            $table->text('alamat_lengkap');
            $table->text('catatan')->nullable();
            $table->decimal('lat', 10, 8)->nullable(); // Latitude untuk Google Maps
            $table->decimal('lng', 11, 8)->nullable(); // Longitude untuk Google Maps
            $table->boolean('is_default')->default(false); // Alamat utama
            $table->timestamps();

            // Foreign key ke tabel pelanggan
            $table->foreign('id_pelanggan')
                  ->references('id_pelanggan')
                  ->on('pelanggan')
                  ->onDelete('cascade');
            
            // Index untuk performa query
            $table->index('id_pelanggan');
            $table->index('is_default');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alamat_pelanggan');
    }
};
