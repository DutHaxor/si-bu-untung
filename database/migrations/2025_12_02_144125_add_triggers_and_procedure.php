<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // --- BEFORE: set harga_satuan dari tabel barang ---
        DB::unprepared("DROP TRIGGER IF EXISTS trg_before_insert_detail;");
        DB::unprepared("
            CREATE TRIGGER trg_before_insert_detail
            BEFORE INSERT ON detail_transaksi
            FOR EACH ROW
            SET NEW.harga_satuan = (
                SELECT b.harga_satuan
                FROM barang b
                WHERE b.id_barang = NEW.id_barang
            )
        ");

        DB::unprepared("DROP TRIGGER IF EXISTS trg_before_update_detail;");
        DB::unprepared("
            CREATE TRIGGER trg_before_update_detail
            BEFORE UPDATE ON detail_transaksi
            FOR EACH ROW
            SET NEW.harga_satuan = (
                SELECT b.harga_satuan
                FROM barang b
                WHERE b.id_barang = NEW.id_barang
            )
        ");

        // --- AFTER: jaga total_transaksi = SUM(qty * harga_satuan) ---
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_insert_detail_sum;");
        DB::unprepared("
            CREATE TRIGGER trg_after_insert_detail_sum
            AFTER INSERT ON detail_transaksi
            FOR EACH ROW
            UPDATE transaksi t
            SET t.total_transaksi = (
                SELECT COALESCE(SUM(dt.qty * dt.harga_satuan), 0)
                FROM detail_transaksi dt
                WHERE dt.id_transaksi = NEW.id_transaksi
            )
            WHERE t.id_transaksi = NEW.id_transaksi
        ");

        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_update_detail_sum;");
        DB::unprepared("
            CREATE TRIGGER trg_after_update_detail_sum
            AFTER UPDATE ON detail_transaksi
            FOR EACH ROW
            UPDATE transaksi t
            SET t.total_transaksi = (
                SELECT COALESCE(SUM(dt.qty * dt.harga_satuan), 0)
                FROM detail_transaksi dt
                WHERE dt.id_transaksi = NEW.id_transaksi
            )
            WHERE t.id_transaksi = NEW.id_transaksi
        ");

        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_delete_detail_sum;");
        DB::unprepared("
            CREATE TRIGGER trg_after_delete_detail_sum
            AFTER DELETE ON detail_transaksi
            FOR EACH ROW
            UPDATE transaksi t
            SET t.total_transaksi = (
                SELECT COALESCE(SUM(dt.qty * dt.harga_satuan), 0)
                FROM detail_transaksi dt
                WHERE dt.id_transaksi = OLD.id_transaksi
            )
            WHERE t.id_transaksi = OLD.id_transaksi
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_before_insert_detail;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_before_update_detail;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_insert_detail_sum;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_update_detail_sum;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_delete_detail_sum;");
    }
};
