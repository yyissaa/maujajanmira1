<?php

namespace App\Models;
// lokasi file ini di dalam folder app/Models

use Illuminate\Database\Eloquent\Factories\HasFactory;
// Ambil trait HasFactory, untuk bikin data dummy lewat factory (kalau diperlukan)
use Illuminate\Database\Eloquent\Model;
// Ambil class Model, induk dari semua model Eloquent di Laravel

class Order extends Model
// Bikin class Order, turunan dari Model
// Class ini mewakili tabel "orders" di database (pesanan)

{
    use HasFactory;
    // Pakai trait HasFactory supaya model ini bisa dipakai untuk testing / seeding

    protected $guarded = ['id'];
    // Kolom "id" TIDAK boleh diisi massal (dilindungi)
    // Kolom lain (customer_name, table_number, total_price, status) BOLEH diisi massal
    // (Tidak perlu tulis $table karena Laravel otomatis nebak jadi "orders" dari nama class Order)

    // Relasi: 1 Pesanan punya banyak detail item
    // Komentar: menjelaskan bahwa method di bawah ini adalah relasi one-to-many

    public function orderDetails()
    // Method untuk ambil semua rincian (detail) dari pesanan ini
    // Nama method = "orderDetails" (bentuk jamak karena hasilnya banyak)
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
        // Artinya: 1 Order PUNYA BANYAK OrderDetail
        // - OrderDetail::class → model tujuan relasi
        // - 'order_id' → kolom penghubung di tabel order_details
        //   (kolom ini menyimpan ID pesanan induknya)
    }
}