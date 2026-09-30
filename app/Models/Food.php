<?php

namespace App\Models;
// lokasi file ini di dalam folder app/Models

use Illuminate\Database\Eloquent\Factories\HasFactory;
// Ambil trait HasFactory,  untuk bikin data dummy lewat factory (kalau diperlukan)
use Illuminate\Database\Eloquent\Model;
// Ambil class Model, induk dari semua model Eloquent di Laravel

class Food extends Model
// Bikin class Food, turunan dari Model
// Class ini mewakili tabel "foods" di database

{
    use HasFactory;
    // Pakai trait HasFactory supaya model ini bisa dipakai untuk testing / seeding

    protected $table = 'foods';
    // Tentukan nama tabel di database: "foods"
    // (kalau tidak ditulis, Laravel otomatis nebak jadi "foods" dari nama class Food — jadi baris ini sebenarnya penegasan)

    protected $guarded = ['id'];
    // Kolom "id" TIDAK boleh diisi massal (dilindungi)
    // Kolom lain (name, category, price, description, image) BOLEH diisi massal
    // Ini kebalikan dari $fillable — pakai $guarded biar lebih ringkas

}