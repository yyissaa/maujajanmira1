<?php

namespace App\Http\Controllers; // Menentukan namespace class ini berada di folder App\Http\Controllers

use App\Models\Food; // Import model Food agar bisa dipakai tanpa App\Models\Food
use Illuminate\Http\Request; // Import class request untuk menerima input dari form
use Illuminate\Support\Facades\Storage; ////Import storage facades

class FoodController extends Controller
{
    public function index() // Tampilkkan daftar makanan
    {
        $foods = Food::latest()->paginate(10); // Ambil semua food, urutan terharu, 10 per halaman
        return view('admin.foods.index', compact('foods')); // tampilkan halaman admin/foods/index, bawa data makanan tadi
    }

    public function create() // menampilkan form tambah makanan
    {
        return view('admin.foods.create'); // tampilkan halaman form tambah
    }

    public function store(Request $request) // fungsi untuk simpan makanan baru ke database
    {
        $request->validate([ // validasi data yang dikirim dari form
            'name'        => 'required|string|max:255', // nama wajib diisi, teks maksimal 255 huruf
            'category'    => 'required|in:Makanan,Minuman,Cemilan', // kategori wajib diisi, hanya bisa diantara 3 pilihan itu
            'price'       => 'required|numeric|min:0', // price harus diisi, angka bulat, minimal 0
            'description' => 'required|string', // deskripsi hrus diisi
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // foto boleh kosong, format harus diantara 3 itu, maks 2mb
        ]);

        $imagePath = null; // wadah untuk gambar

        if ($request->hasFile('image')) { // kalau user upload gambar
            $imagePath = $request->file('image')->store('foods', 'public'); // simpan gambar ke folder foods, simpan alamatnya di $imagePath
        }

        Food::create([ // simpan data makanan baru ke database
            'name'        => $request->name, // isi kolom nama
            'category'    => $request->category, // isi kolom kategori
            'price'       => $request->price, // isi kolom harga
            'description' => $request->description, // isi kolom deskripsi
            'image'       => $imagePath, // isi kolom image, bisa null 
        ]);

        return redirect()->route('foods.index') // pindah ke halaman daftar makanan + pesan berhasil
            ->with('success', 'Data makanan berhasil ditambahkan!');
    }

    public function edit(Food $food) // fungsi untuk menampilkan form edit makanan, $food otomatis dicari  laravel berdasarkan ID 
    {
        return view('admin.foods.edit', compact('food')); // menampilkan halaman edit sambil bawa data makanan yang ingin diedit
    }

    public function update(Request $request, Food $food) // fungsi untuk simpan perubahan makanan
    {
        $request->validate([ //validasi data yang dikirim dari form
            'name'        => 'required|string|max:255', // nama wajib diisi, teks maksimal 255 huruf
            'category'    => 'required|in:Makanan,Minuman,Cemilan', // kategori wajib diisi, hanya bisa diantara 3 pilihan itu
            'price'       => 'required|numeric|min:0', // price harus diisi, angka bulat, minimal 0
            'description' => 'required|string', // deskripsi harus diisi
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // foto boleh kosong, format harus diantara 3 itu, maks 2mb
        ]);

        $imagePath = $food->image; // simpan dulu gambar lama (jika user tidak ganti gambar)

        if ($request->hasFile('image')) { // kalau user upload gambar baru
            if ($food->image && Storage::disk('public')->exists($food->image)) { // cek dahulugambar lama ada dan filenya benar ada di storage
                Storage::disk('public')->delete($food->image); // hapus gambar lama
            }
            $imagePath = $request->file('image')->store('foods', 'public'); // simpan gambar baru
        }

        $food->update([ // update data makanan
            'name'        => $request->name, //update nama
            'category'    => $request->category, // update kategori
            'price'       => $request->price, // update harga
            'description' => $request->description, // update deskripsi
            'image'       => $imagePath, //update gambar (lama atau baru)
        ]);

        return redirect()->route('foods.index') // balik ke daftar makanan + pesan berhasil
            ->with('success', 'Data makanan berhasil diperbarui!');
    }

    public function destroy(Food $food) // fungsi untuk hapus makanan
    {
        if ($food->image && Storage::disk('public')->exists($food->image)) { // cek dahulu gambar ada dan filenya benar ada di storage
            Storage::disk('public')->delete($food->image); // hapus file gambarnya
        }

        $food->delete(); // hapus data makanan dari database

        return redirect()->route('foods.index') // balik ke daftar + pesan berhasil
            ->with('success', 'Data makanan berhasil dihapus!');
    }
}