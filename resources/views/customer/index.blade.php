<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"> <!-- agar huruf tampil benar -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- agar tampilan pas di HP -->
    <title>Menu Restoran</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- ambil tailwind dari internet -->
</head>
<body class="bg-gray-100 p-6 font-sans">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Menu Restoran</h1>
            <p class="text-gray-500 text-sm mt-1">Pilih menu makanan dan masukkan nomor meja Anda</p>
        </div>

        <!-- NOTIFIKASI SUCCESS / ERROR / VALIDASI -->
        @if(session('success')) <!-- untu kotak notifikasi sukses (kalau ada pesan sukses dari server) -->
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 text-center font-semibold">{{ session('success') }}</div>
        @endif

        @if(session('error')) <!-- kotak notifikasi error (kalau ada pesan error dari server) -->
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 text-center font-semibold">{{ session('error') }}</div>
        @endif

        @if($errors->any()) <!-- untuk cek apakah ada error validasi dari form -->
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 font-semibold">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error) <!-- ulangi setiap pesan error -->
                        <li>{{ $error }}</li> <!-- tampilkan 1 pesan error -->
                    @endforeach <!-- selesai loop -->
                </ul>
            </div>
        @endif <!-- tutup pengecekan validasi -->

        <!-- FILTER KATEGORI (JAVASCRIPT) -->
        <div class="flex flex-wrap justify-center gap-3 mb-8"> 
            <!-- tombol "semua menu" aktif, panggil JS filterCategory -->
            <button type="button" onclick="filterCategory('all', this)" class="btn-category px-5 py-2 rounded-full font-semibold text-sm transition bg-blue-600 text-white shadow-md">Semua Menu</button>
            <!-- tombol "makanan", putih, panggil JS filterCategory('makanan') -->
            <button type="button" onclick="filterCategory('Makanan', this)" class="btn-category px-5 py-2 rounded-full font-semibold text-sm transition bg-white text-gray-600 hover:bg-gray-200 border">Makanan</button>
            <!-- tombol "minuman", putih, panggil JS filterCategory('minuman') -->
            <button type="button" onclick="filterCategory('Minuman', this)" class="btn-category px-5 py-2 rounded-full font-semibold text-sm transition bg-white text-gray-600 hover:bg-gray-200 border">Minuman</button>
            <!-- tombol "cemilan", putih, panggil JS filterCategory('cemilan') -->
            <button type="button" onclick="filterCategory('Cemilan', this)" class="btn-category px-5 py-2 rounded-full font-semibold text-sm transition bg-white text-gray-600 hover:bg-gray-200 border">Cemilan</button>
        </div>

        <!-- form pesanan, kirim ke route customer.checkout via POST -->
        <form id="orderForm" action="{{ route('customer.checkout') }}" method="POST">
            @csrf <!-- token keamanan wajib Laravel -->

            <!-- 1. Informasi Pemesan -->
            <div class="bg-white p-6 rounded-xl shadow-sm border mb-6">
                <h2 class="text-lg font-bold text-gray-700 mb-4 pb-2 border-b">1. Informasi Pemesan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                        <input type="text" id="customer_name" name="customer_name" required placeholder="Masukkan nama pemesan" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Nomor Meja</label>
                        <input type="number" id="table_number" name="table_number" required placeholder="Contoh: 05" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
            </div>

            <!-- 2. Pilih Menu -->
            <h2 class="text-lg font-bold text-gray-700 mb-4">2. Pilih Menu</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($foods as $food) <!-- untuk ulangi setiap makanan dari controller -->
                    <div class="food-card bg-white rounded-xl shadow-sm border overflow-hidden flex flex-col justify-between" data-category="{{ $food->category ?? 'Makanan' }}">
                        <div>
                            @if($food->image) <!-- untuk cek apakah makanan punya gambar -->
                                <!-- tampilkan gambar makanan -->
                                <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}" class="w-full h-40 object-cover">
                            @else <!-- kalau tidak ada gambar -->
                                <!-- kotak abu -->
                                <div class="bg-gray-200 h-40 flex items-center justify-center text-gray-400 font-medium">Tanpa Gambar</div>
                            @endif

                            <div class="p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <!-- badge kategori -->
                                    <span class="text-xs bg-blue-100 text-blue-700 font-semibold px-2.5 py-0.5 rounded">{{ $food->category ?? 'Makanan' }}</span>
                                    <!-- harga dengqn format -->
                                    <span class="font-bold text-green-600">Rp {{ number_format($food->price) }}</span>
                                </div>
                                <h3 class="font-bold text-gray-800 text-lg item-name">{{ $food->name }}</h3> <!-- nama makanan -->
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $food->description }}</p> <!-- deskripsi, maks 2 baris -->
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50 border-t">
                            <!-- label jumlah porsi -->
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Jumlah Porsi</label>
                            <!-- input jumlah: name items[ID] jadi array, data-name dan data-price untuk JS hitung total -->
                            <input type="number" name="items[{{ $food->id }}]" min="0" value="0" data-name="{{ $food->name }}" data-price="{{ $food->price }}" class="item-qty w-full border rounded-lg px-3 py-1.5 text-center font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 text-right">
                <button type="button" onclick="showConfirmationModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-xl shadow-md transition">Pesan Sekarang</button>
            </div>

            <!-- MODAL KONFIRMASI PESANAN (DI DALAM FORM) -->
            <div id="confirmModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
                <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl transform transition-all">
                    <h3 class="text-xl font-bold text-gray-800 border-b pb-3 mb-4">Konfirmasi Pesanan</h3>
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <div class="flex justify-between"><span class="font-semibold">Nama:</span> <span id="modalName" class="text-gray-900 font-bold"></span></div>
                        <div class="flex justify-between"><span class="font-semibold">No. Meja:</span> <span id="modalTable" class="text-gray-900 font-bold"></span></div>
                    </div>
                    <div class="border-t border-b py-3 mb-4 max-h-48 overflow-y-auto">
                        <p class="font-semibold text-xs text-gray-400 uppercase mb-2">Rincian Item</p>
                        <ul id="modalItemList" class="space-y-2 text-sm"></ul>
                    </div>
                    <div class="flex justify-between items-center text-lg font-bold text-gray-800 mb-6">
                        <span>Total Pembayaran:</span>
                        <span id="modalTotalPrice" class="text-green-600 text-xl">Rp 0</span>
                    </div>
                    <div class="flex gap-3"> 
                        <button type="button" onclick="closeConfirmationModal()" class="w-1/2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2.5 rounded-xl transition">Batal</button>
                        <button type="submit" class="w-1/2 bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl shadow transition">Ya, Kirim Pesanan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function filterCategory(category, element) { // fungsi filter menu berdasarkan katgeori
            document.querySelectorAll('.btn-category').forEach(btn => { // ambil semua tombol kategori
                // reset semua tombol jadi putih
                btn.className = "btn-category px-5 py-2 rounded-full font-semibold text-sm transition bg-white text-gray-600 hover:bg-gray-200 border";
            });
            // tombol yang diklik jadi biru
            element.className = "btn-category px-5 py-2 rounded-full font-semibold text-sm transition bg-blue-600 text-white shadow-md";
            // ambil semua kartu makanan
            const cards = document.querySelectorAll('.food-card');
            cards.forEach(card => { // untuk setiap kartu
                const cardCategory = card.getAttribute('data-category'); // baca kategori kartu
                // tampilkan kalau cocok, sembunyikan kalau tidak
                card.style.display = (category === 'all' || cardCategory === category) ? 'flex' : 'none';
            });
        }

        function showConfirmationModal() { // fungsi tampilkan modal konfirmasi 
            const name = document.getElementById('customer_name').value.trim(); // ambil nama pelanggan
            const table = document.getElementById('table_number').value.trim(); // ambil nomor meja
            if (!name || !table) { // kalau salah satu kosong
                alert('Silakan isi Nama Lengkap dan Nomor Meja terlebih dahulu!'); // kasih peringatan
                return; // stop
            }
            const items = document.querySelectorAll('.item-qty'); // ambil semua input jumlah
            let itemListHtml = ''; //wadah HTML daftar item
            let grandTotal = 0; //wadah total harga
            let hasOrder = false; // tanda apakah ada item dipesan 

            items.forEach(input => { //untuk setiap input jumlah
                const qty = parseInt(input.value) || 0; // baca jumlah sebagai angka
                if (qty > 0) { // kalau lebih dari 0
                    hasOrder = true; // tandai ada item
                    const itemName = input.getAttribute('data-name'); // ambil nama makanan
                    const price = parseFloat(input.getAttribute('data-price')); // ambil harga
                    const subtotal = qty * price; // hitung subtotal
                    grandTotal += subtotal; // tambah ke total
                    // tambah baris HTML item
                    itemListHtml += `<li class="flex justify-between items-center"><div><span class="font-bold text-gray-800">${itemName}</span><span class="text-xs text-gray-500 block">x${qty} @ Rp ${price.toLocaleString('id-ID')}</span></div><span class="font-semibold text-gray-700">Rp ${subtotal.toLocaleString('id-ID')}</span></li>`;
                }
            });

            if (!hasOrder) { // kalau tidak ada item yang dipesan
                alert('Pilih minimal 1 menu makanan/minuman dengan jumlah lebih dari 0!'); // kasih peringatan
                return; // stop
            }

            document.getElementById('modalName').textContent = name; //isi nama di modal 
            document.getElementById('modalTable').textContent = table; // isi no meja di modal
            document.getElementById('modalItemList').innerHTML = itemListHtml; // isi daftar item di modal
            //isi total di modal
            document.getElementById('modalTotalPrice').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');

            const modal = document.getElementById('confirmModal'); // ambil elemen modal
            modal.classList.remove('hidden'); // hilangkan hidden
            modal.classList.add('flex'); // tampilkan flex
        }

        function closeConfirmationModal() { //fungsi tutup modal
            const modal = document.getElementById('confirmModal'); //ambil elemen modal
            modal.classList.remove('flex'); // hilangkan flex
            modal.classList.add('hidden'); // sembunyikan lagi
        }
    </script>
</body>
</html>