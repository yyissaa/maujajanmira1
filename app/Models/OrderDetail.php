<?php

namespace App\Models;
// lokasi file ini di dalam folder app/Models

use Illuminate\Database\Eloquent\Factories\HasFactory;
// Ambil trait HasFactory, untuk bikin data dummy lewat factory (kalau diperlukan)
use Illuminate\Database\Eloquent\Model;
// Ambil class Model, induk dari semua model Eloquent di Laravel

class OrderDetail extends Model
// Bikin class OrderDetail, turunan dari Model
// Class ini mewakili tabel "order_details" di database (rincian pesanan)

{
    use HasFactory;
    // Pakai trait HasFactory supaya model ini bisa dipakai untuk testing / seeding

    protected $table = 'order_details';
    // Tentukan nama tabel di database: "order_details"
    // (kalau tidak ditulis, Laravel otomatis nebak jadi "order_details" dari nama class OrderDetail — jadi baris ini penegasan)

    // Gunakan guarded agar kolom 'subtotal' diizinkan masuk ke database
    // Komentar: menjelaskan alasan pakai $guarded — supaya kolom subtotal (dan lainnya) bisa diisi massal
    protected $guarded = ['id'];
    // Kolom "id" TIDAK boleh diisi massal (dilindungi)
    // Kolom lain (order_id, food_id, quantity, subtotal) BOLEH diisi massal
    // Dengan kata lain: selain 'id', semua kolom bisa diisi lewat create()/update()

    // Relasi balik ke model Food
    // Komentar: menjelaskan method di bawah ini untuk ambil data makanan dari detail ini
    public function food()
    // Method untuk ambil data makanan yang dipesan di detail ini
    // Nama method = "food" (bentuk tunggal, karena hasilnya 1 makanan)
    {
        return $this->belongsTo(Food::class, 'food_id');
        // Artinya: 1 OrderDetail MILIK 1 Food
        // - Food::class → model tujuan relasi
        // - 'food_id' → kolom penghubung di tabel order_details
        //   (kolom ini menyimpan ID makanan)
    }

    // Relasi balik ke model Order
    // Komentar: menjelaskan method di bawah ini untuk ambil pesanan induknya
    public function order()
    // Method untuk ambil data pesanan (order) induk dari detail ini
    // Nama method = "order" (bentuk tunggal, karena hasilnya 1 pesanan)
    {
        return $this->belongsTo(Order::class, 'order_id');
        // Artinya: 1 OrderDetail MILIK 1 Order
        // - Order::class → model tujuan relasi
        // - 'order_id' → kolom penghubung di tabel order_details
        //   (kolom ini menyimpan ID pesanan induknya)
    }
}
// Tutup class OrderDetail