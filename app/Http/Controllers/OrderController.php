<?php
namespace App\Http\Controllers;

use App\Models\Food; // ambil model food
use App\Models\Order; // ambil model order
use App\Models\OrderDetail; // ambil model orderdetail
use Illuminate\Http\Request; //ambill class request
use Illuminate\Support\Facades\DB; // ambil DB untuk urusan transaksi database

class OrderController extends Controller // membuat class ordercontroller
{
    /**
     * Display a listing of the resource.
     */
    public function index() // fungsi untuk menampilkan menu ke pelanggan
    {
        $foods = Food::all(); // ambil semua makanan dari database
        return view('customer.index', compact('foods')); //tampilkan halaman menu pelanggan
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // fungsi untuk simpan pesanan pelanggan
    {
        $request->validate([ // validasi input  dari form
            'customer_name' => 'required|string|max:255', // nama wajib, teks, maks 255 huruf
            'table_number'  => 'required|integer|min:1', // nomor meja wajib, angka, min 1
            'items'         => 'required|array', // items wajib ada, bentuknya array
            'items.*'       => 'nullable|integer|min:0', //tiap item boleh kosong, angka, min 0
        ]);

        $orderedItems = array_filter($request->items, fn ($qty) => $qty > 0); //ambil cuma item yang jumlahnya lebih dari 0

        if (empty($orderedItems)) { // kalau tidak ada item yang dipesan
            return back()->with('error', 'Pilih minimal satu menu makanan!'); // balik ke form + pesan error
        }

        DB::beginTransaction(); // mulai transaksi database (semua perintah dianggap satu paket)
        try { // coba jalankan
            $order = Order::create([ // buat pesanan baru
                'customer_name' => $request->customer_name, // simpan nama pelanggan
                'table_number'  => $request->table_number, //simpan nomor meja
                'total_price'   => 0, // sementara 0, nanti dihitung
                'status'        => 'Pending', // status awal "pending"
            ]);

            $totalPrice = 0; // siapkan wadah total harga

            foreach ($orderedItems as $foodId => $quantity) { // ulangi unyuk setiap makanan yang dipesan
                $food = Food::findOrFail($foodId); // cari makanan berdasarkan ID
                $subtotal = $food->price * $quantity; // hitung harga x jumlah
                $totalPrice += $subtotal; // tambahkan ke total 

                OrderDetail::create([ // simpan rincian pesanan
                    'order_id' => $order->id, // sambungkan ke pesanan induk
                    'food_id'  => $food->id, // simpan ID makanan
                    'quantity' => $quantity, // simpan jumlah
                    'subtotal' => $subtotal, // Disimpan ke kolom subtotal
                ]);
            }

            $order->update(['total_price' => $totalPrice]); // update total harga pesanan
            DB::commit(); // kalau semua sukses, kunci transaksi

            return redirect()->route('customer.index')->with('success', 'Pesanan berhasil dibuat: Nomor Meja: ' , $order->table_number); // bslik ke halaman menu + pesan sukses
        } catch (\Exception $e) { // kalau ada error di tengah jalan
            DB::rollBack(); // batalkan semua perubahan datbase 
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage()); // balik ke form + pesan error
        }
    }

    public function adminDashboard() // fungsi untuk tampilkan daftar pesanan ke admin
    {
        $orders = Order::with('orderDetails.food')->latest()->get(); // ambil semua pesanan sekalian ambil rincian dan makanannya
        return view('dashboard', compact('orders')); // tampilkan halaman dashboard admin
    }

    public function updateStatus(Request $request, $id)  // fungsi untuk ubah status pesanan
    {
        $request->validate(['status' => 'required|string']); // validasi status wajib diisi, teks
        $order = Order::findOrFail($id); // cari pesanan berdasarkan ID
        $order->update(['status' => $request->status]); // untuk update status nya

        return back()->with('success', 'Status pesanan #' . $order->id . ' Berhasil diperbarui!'); // balik ke halaman sebelumnya dan pesanan sukses
    }
}
