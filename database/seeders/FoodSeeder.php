<?php //buat menambahkan data

namespace Database\Seeders;
// Menentukan lokasi file ini di dalam folder database/seeders

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// Ambil trait WithoutModelEvents, untuk mematikan event model saat seeding

use Illuminate\Database\Seeder;
// Ambil class Seeder, induk dari semua file seeder di Laravel

use Illuminate\Support\Facades\DB;
// Ambil DB facade, untuk jalankan query langsung ke database
// (dipakai untuk insert data tanpa lewat model)

class FoodSeeder extends Seeder
// Bikin class FoodSeeder, turunan dari Seeder
// Seeder ini khusus untuk isi data awal ke tabel "foods"

{
    /**
     * Run the database seeds.
     */


    public function run(): void
    // Method run() → dijalankan saat seeder ini dipanggil
    // "void" artinya method ini tidak mengembalikan nilai
    {
        DB::table('foods')->insert([
        // Insert data ke tabel "foods" (langsung lewat query builder, bukan model)
        // insert([...]) → bisa insert banyak baris sekaligus dalam satu perintah

            // ── Data ke-1 ─────────────────────────────
            [
                'name' => 'Nasi Goreng Spesial', // Nama makanan
                'category' => 'Makanan', // Kategori (harus salah satu dari ENUM: Makanan / Minuman / Cemilan)
                'price' => 25000, // Harga (angka, tanpa titik/koma)
                'description' => 'Nasi goreng dengan telur, ayam suwir, dan kerupuk.', // Deskripsi makanan
                'image' => null, // Tidak ada gambar (nullable, jadi boleh null)
                'created_at' => now(),
                // Waktu record dibuat, now() = waktu saat ini
                'updated_at' => now(),
                // Waktu record diubah, diisi waktu saat ini juga
            ],

            [
                'name' => 'Mie Goreng Seafood',
                'category' => 'Makanan',
                'price' => 28000,
                'description' => 'Mie goreng pedas dengan udang dan cumi.',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Es Teh Manis',
                'category' => 'Minuman', // Kategori: Minuman
                'price' => 5000,
                'description' => 'Es teh melati segar.',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Jus Alpukat',
                'category' => 'Minuman',
                'price' => 15000,
                'description' => 'Jus alpukat murni dengan susu cokelat.',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Kentang Goreng',
                'category' => 'Cemilan', // Kategori: Cemilan
                'price' => 12000,
                'description' => 'Kentang goreng renyah dengan saus keju.',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}