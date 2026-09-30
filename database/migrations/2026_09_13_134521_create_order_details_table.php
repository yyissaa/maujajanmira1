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
        Schema::create('order_details', function (Blueprint $table) {
        // Buat tabel baru bernama "order_details" di database
        // Isi tabelnya diatur lewat closure (function) yang menerima $table

            $table->id();
            // Bikin kolom "id" — otomatis:

            // Foreign Key ke tabel orders
            // kolom di bawah ini nyambung ke tabel orders
            $table->foreignId('order_id')
            // Bikin kolom "order_id"
            // foreignId() itu shortcut untuk bikin kolom sekaligus menandai sebagai foreign key
                  ->constrained('orders')
                  // Tentukan bahwa kolom ini merujuk ke tabel "orders" (kolom id di tabel orders)
                  ->onDelete('cascade');
                  // Kalau data di tabel orders dihapus, data di order_details yang merujuk ke situ IKUT TERHAPUS OTOMATIS

            // Foreign Key ke tabel foods
            // kolom di bawah ini nyambung ke tabel foods
            $table->foreignId('food_id')
            // Bikin kolom "food_id" 
                  ->constrained('foods')
                  // Tentukan bahwa kolom ini merujuk ke tabel "foods" (kolom id di tabel foods)
                  ->onDelete('cascade');
                  // Kalau data makanan dihapus, detail pesanan yang merujuk ke makanan itu IKUT TERHAPUS OTOMATIS

            $table->integer('quantity');
            // Bikin kolom "quantity" tipe INTEGER (angka bulat)
            // untuk menyimpan jumlah porsi yang dipesan

            $table->integer('subtotal');
            // Bikin kolom "subtotal" tipe INTEGER (angka bulat)
            // untuk menyimpan harga total dari 1 baris pesanan ini
            // (contoh: Nasi Goreng 25.000 × 2 = subtotal 50.000)

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
        Schema::dropIfExists('order_details');
        // Hapus tabel "order_details" kalau ada
        // Nama tabel cocok dengan yang di up() — konsisten 
    }
};