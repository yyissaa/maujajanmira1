# 🍽️ Aplikasi Pemesanan Makanan

Aplikasi web pemesanan makanan restoran berbasis Laravel. Pelanggan dapat melihat menu, memilih makanan, dan melakukan pemesanan. Admin dapat mengelola menu dan memproses pesanan.

---

## 📋 Fitur

### Sisi Pelanggan (Publik)
- Melihat daftar menu makanan
- Filter menu berdasarkan kategori (Makanan, Minuman, Cemilan)
- Memilih makanan & mengatur jumlah porsi
- Mengisi nama & nomor meja
- Konfirmasi pesanan sebelum checkout
- Melakukan checkout pesanan

### Sisi Admin (Login Diperlukan)
- Login dengan akun admin
- Melihat rekap pesanan yang masuk
- Mengubah status pesanan (Pending → Diproses → Selesai)
- CRUD (Tambah, Lihat, Edit, Hapus) data makanan
- Upload gambar makanan saat tambah/edit

---

## 🛠️ Teknologi yang Digunakan

| Teknologi | Versi | Fungsi |
|-----------|-------|--------|
| PHP | - | Bahasa pemrograman utama |
| Laravel | - | Framework PHP |
| MySQL | - | Database |
| Tailwind CSS | CDN | Styling tampilan |
| Laravel Breeze | - | Autentikasi (login/register) |

---

## 📦 Persyaratan Sistem

Sebelum instalasi, pastikan sudah terpasang:

- **XAMPP / Laragon** (untuk Apache & MySQL)
- **PHP** versi 8.1 atau lebih baru
- **VS Code** (untuk edit kode)

---

## 🚀 Cara Instalasi

### 1. Clone / Download Project

```bash
git clone <url-repository>
cd pesanmakan