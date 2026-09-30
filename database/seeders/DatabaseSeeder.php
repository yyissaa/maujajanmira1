<?php 

namespace Database\Seeders;
// Menentukan lokasi file ini di dalam folder database/seeders

use App\Models\User;
// Ambil model User, untuk bikin data user (admin) di database

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// Ambil trait WithoutModelEvents untuk mematikan event model saat seeding
// (biar seeding lebih cepat & tidak trigger event tidak perlu)

use Illuminate\Database\Seeder;
// Ambil class Seeder, induk dari semua file seeder di Laravel

use Illuminate\Support\Facades\Hash;
// Ambil Hash facade, untuk enkripsi password (biar tidak disimpan sebagai teks biasa)

class DatabaseSeeder extends Seeder
// Bikin class DatabaseSeeder, turunan dari Seeder
// Ini adalah seeder UTAMA yang dipanggil saat perintah "php artisan db:seed"

{
    use WithoutModelEvents;
    // Pakai trait WithoutModelEvents //Trait = kumpulan method (fungsi) yang bisa dipakai ulang di banyak class, tanpa harus bikin warisan (extends).
    // Fungsinya: mematikan event Eloquent (created, updated, dll) selama seeding
    // Biar tidak ada efek samping yang tidak diinginkan

    /**
     * Seed the application's database.
     */

    public function run(): void
    // Method run() → dijalankan saat perintah "php artisan db:seed"
    // "void" artinya method ini tidak mengembalikan nilai
    {
        // User::factory(10)->create();
        // Baris ini DIKOMENTARI (tidak dijalankan)
        // Kalau aktif: bikin 10 user dummy otomatis via factory

        // 1. Buat Akun Admin Bawaan untuk Uji Coba Asesor
        // bikin 1 akun admin langsung (tanpa factory)
        User::create([
        // Simpan user baru ke database
            'name' => 'Admin Toko', // Nama admin
            'email' => 'admin@gmail.com', // Email admin — dipakai untuk login
            'password' => Hash::make('password123'),
            // Password di-HASH (dienkripsi) dengan "password123"
            // Wajib pakai Hash::make() supaya aman & bisa diverifikasi Laravel saat login
        ]);

        // 2. Panggil Seeder Makanan
        // Komentar: panggil seeder lain untuk isi data makanan
        $this->call([
        // Panggil seeder lain dari dalam seeder ini
            FoodSeeder::class,
            // Jalankan class FoodSeeder, isi tabel foods dengan data awal
        ]);
    }
}