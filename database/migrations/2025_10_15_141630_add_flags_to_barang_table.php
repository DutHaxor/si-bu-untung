<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            if (!Schema::hasColumn('barang', 'is_recommended')) {
                $table->boolean('is_recommended')->default(false);
            }
            if (!Schema::hasColumn('barang', 'sold_count')) {
                $table->unsignedInteger('sold_count')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            if (Schema::hasColumn('barang', 'is_recommended')) {
                $table->dropColumn('is_recommended');
            }
            if (Schema::hasColumn('barang', 'sold_count')) {
                $table->dropColumn('sold_count');
            }
        });
    }
};