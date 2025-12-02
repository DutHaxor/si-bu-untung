<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1) Pastikan kolom flags ada
        if (!Schema::hasColumn('barang','is_recommended')) {
            Schema::table('barang', function ($t) {
                $t->boolean('is_recommended')->default(false)->index();
            });
        }
        if (!Schema::hasColumn('barang','sold_count')) {
            Schema::table('barang', function ($t) {
                $t->unsignedInteger('sold_count')->default(0)->index();
            });
        }

        // 2) Bikin tanggal_kedaluwarsa nullable (jika perlu)
        // Sesuaikan tipe kolom di DB kamu: DATE/DATETIME/TIMESTAMP
        DB::statement('ALTER TABLE barang MODIFY tanggal_kedaluwarsa DATE NULL');

        // 3) Ubah id_barang jadi VARCHAR dan jadikan PRIMARY KEY non-increment
        // Lepas FK constraint dulu (jika ada), lalu PK, lalu modify, lalu set lagi
        
        // Drop foreign key constraint dari detail_transaksi jika ada
        // Coba beberapa nama constraint yang mungkin
        $possibleConstraintNames = [
            'detail_transaksi_id_barang_foreign',
            'detail_transaksi_id_barang_barang_id_barang_foreign',
        ];
        
        foreach ($possibleConstraintNames as $constraintName) {
            try {
                DB::statement("ALTER TABLE detail_transaksi DROP FOREIGN KEY `{$constraintName}`");
            } catch (\Exception $e) {
                // Constraint tidak ada, lanjutkan
            }
        }
        
        // Cek semua foreign key yang mengacu ke barang.id_barang
        if (Schema::hasTable('detail_transaksi')) {
            try {
                $fks = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'detail_transaksi' 
                    AND COLUMN_NAME = 'id_barang' 
                    AND REFERENCED_TABLE_NAME = 'barang'
                ");
                foreach ($fks as $fk) {
                    $constraintName = $fk->CONSTRAINT_NAME;
                    try {
                        DB::statement("ALTER TABLE detail_transaksi DROP FOREIGN KEY `{$constraintName}`");
                    } catch (\Exception $e) {
                        // Skip jika error
                    }
                }
            } catch (\Exception $e) {
                // Skip jika error
            }
        }
        
        // Drop primary key
        DB::statement('ALTER TABLE barang DROP PRIMARY KEY');
        
        // Modify column
        DB::statement('ALTER TABLE barang MODIFY id_barang VARCHAR(16) NOT NULL');
        
        // Re-add primary key
        DB::statement('ALTER TABLE barang ADD PRIMARY KEY (id_barang)');
        
        // Re-add foreign key constraint (jika tabel detail_transaksi ada)
        if (Schema::hasTable('detail_transaksi')) {
            try {
                DB::statement("
                    ALTER TABLE detail_transaksi 
                    ADD CONSTRAINT detail_transaksi_id_barang_foreign 
                    FOREIGN KEY (id_barang) 
                    REFERENCES barang(id_barang) 
                    ON UPDATE CASCADE 
                    ON DELETE RESTRICT
                ");
            } catch (\Exception $e) {
                // Jika constraint sudah ada atau error, skip
            }
        }
    }

    public function down(): void
    {
        // Rollback best-effort (kembalikan ke INT tanpa AI)
        DB::statement('ALTER TABLE barang DROP PRIMARY KEY');
        DB::statement('ALTER TABLE barang MODIFY id_barang INT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE barang ADD PRIMARY KEY (id_barang)');

        // (opsional) balikan nullable
        DB::statement('ALTER TABLE barang MODIFY tanggal_kedaluwarsa DATE NOT NULL');

        // (opsional) hapus flags
        Schema::table('barang', function ($t) {
            if (Schema::hasColumn('barang','is_recommended')) $t->dropColumn('is_recommended');
            if (Schema::hasColumn('barang','sold_count'))     $t->dropColumn('sold_count');
        });
    }
};
