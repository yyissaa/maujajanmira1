<?php

use Illuminate\Database\Migrations\Migration;
// Ambil class Migration, induk dari semua file migration di Laravel

use Illuminate\Database\Schema\Blueprint;
// Ambil class Blueprint, "alat" untuk mendefinisikan struktur tabel

use Illuminate\Support\Facades\Schema;
// Ambil Schema facade, untuk bikin/hapus tabel di database

return new class extends Migration
// Kembalikan class anonymous (tanpa nama) yang turunan dari Migration
// Laravel pakai gaya ini supaya file migration tidak perlu nama class
{
    /**
     * Run the migrations.
     */
    

    public function up(): void
    // Method up() → dijalankan waktu perintah "php artisan migrate"
    // "void" artinya method ini tidak mengembalikan nilai
    {
        Schema::create('orders', function (Blueprint $table) {
        //buat tabel baru bernama "orders" di database
        //isi tabelnya diatur lewat closure (function) yang menerima $table

            $table->id(); // Primary Key
            // bikin kolom "id" — otomatis:

            $table->string('customer_name');
            // Bikin kolom "customer_name"
            // untuk menyimpan nama pelanggan yang memesan

            $table->string('table_number'); // Bisa Integer/Varchar (disarankan string/varchar)
            // Bikin kolom "table_number"
            // Kenapa string, bukan integer?
            // - Supaya bisa tampung nomor meja seperti "05" (dengan nol di depan)
            // - Kalau pakai integer, "05" otomatis jadi "5"

            $table->integer('total_price');
            // Bikin kolom "total_price" tipe INTEGER (angka bulat)
            // untuk menyimpan total harga pesanan (dihitung dari subtotal semua item)

            $table->string('status')->default('Pending');
            // Bikin kolom "status"
            // default('Pending') → kalau tidak diisi, otomatis berisi "Pending"
            //nntia bisa diubah admin jadi "Diproses" atau "Selesai"

            $table->timestamps();
            // Bikin 2 kolom sekaligus:
            // - created_at (waktu record dibuat)
            // - updated_at (waktu record terakhir diubah)
            // Keduanya otomatis diisi Laravel
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    // Method down() → dijalankan waktu perintah "php artisan migrate:rollback"
    // untuk membatalkan migration ini
    {
        Schema::dropIfExists('orders');
        // Hapus tabel "orders" kalau ada
        // Nama tabel di sini sudah cocok dengan yang di up() — konsisten
    }
};