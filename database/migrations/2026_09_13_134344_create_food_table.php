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
        Schema::create('foods', function (Blueprint $table) {
        // Buat tabel baru bernama "foods" di database
        // Isi tabelnya diatur lewat closure (function) yang menerima $table

            $table->id(); // Primary Key
            // Bikin kolom "id" otomatis

            $table->string('name');
            // Bikin kolom "name" (teks pendek, max 255)
            // untuk menyimpan nama makanan

            $table->enum('category', ['Makanan', 'Minuman', 'Cemilan']);
            // Bikin kolom "category" tipe ENUM
            // Hanya boleh diisi salah satu dari 3, Makanan, Minuman, Cemilan

            $table->integer('price');
            // Bikin kolom "price" tipe INTEGER (angka bulat)
            // untuk menyimpan harga makanan

            $table->text('description');
            // Bikin kolom "description" tipe TEXT 
            // untuk menyimpan deskripsi makanan

            $table->string('image')->nullable(); // Boleh Kosong/Null
            // Bikin kolom "image" 
            // nullable() → boleh diisi NULL (kalau makanan tidak punya gambar)

            $table->timestamps(); // created_at & updated_at
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
        Schema::dropIfExists('food');
        // Hapus tabel "food" kalau ada
        // Catatan: di sini tertulis 'food' (tanpa s), padahal di up() namanya 'foods'
    }
};