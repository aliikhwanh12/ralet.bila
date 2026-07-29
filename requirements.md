# Requirements — Website Pembelian Akun Premium Sharing

## 1. Ringkasan Produk

Website sederhana untuk menjual **akun premium sharing** (contoh: Netflix, Spotify, YouTube Premium, Canva Pro, dll). Pembeli memilih produk, melakukan pembayaran melalui **QRIS statis**, lalu mengirim bukti transaksi ke **WhatsApp** admin. Tidak ada payment gateway otomatis — konfirmasi pembayaran dilakukan manual oleh admin.

- **Tech stack:** Laravel (backend + Blade templating), Bootstrap 5 (frontend).
- **Database:** SQLite.
- **Warna utama:** Biru muda langit (sky blue, `#87CEEB` sebagai aksen; palet lengkap di `design.md`).
- **Bahasa UI:** Indonesia.
- **Prinsip desain:** Sesimpel mungkin, minimal langkah, mobile-friendly.

## 2. Aktor / Pengguna

| Aktor | Deskripsi |
|-------|-----------|
| **Customer (Pembeli)** | Publik, tanpa login. Memilih produk, membayar, kirim bukti ke WA. |
| **Admin** | Login untuk mengelola produk, melihat penjualan, dan mengatur pengaturan situs. |

## 3. Functional Requirements

### 3.1 Sisi Customer

- **FR-C1 — Katalog Produk**
  - Menampilkan daftar produk premium yang aktif (nama, gambar/logo, deskripsi singkat, durasi, harga).
  - Produk yang di-nonaktifkan admin tidak tampil.
  - Produk dengan stok habis ditandai (badge "Stok Habis") dan tidak bisa dibeli.

- **FR-C2 — Detail Produk**
  - Menampilkan detail lengkap: deskripsi, harga, durasi langganan, syarat & ketentuan sharing.

- **FR-C3 — Checkout / Buat Pesanan**
  - Customer mengisi data minimal: nama, nomor WhatsApp, email (opsional).
  - Memilih jumlah (default 1).
  - Sistem membuat order dengan status awal `pending` dan kode order unik.

- **FR-C4 — Halaman Pembayaran (QRIS Statis)**
  - Menampilkan gambar **QRIS statis** (di-upload admin lewat settings).
  - Menampilkan total yang harus dibayar, kode order, dan instruksi pembayaran.
  - Tombol **"Sudah Bayar — Kirim Bukti via WhatsApp"** yang membuka WhatsApp (link `wa.me`) dengan pesan pre-filled berisi kode order + detail produk.

- **FR-C5 — Konfirmasi via WhatsApp**
  - Nomor WA tujuan diambil dari pengaturan admin.
  - Pesan pre-filled otomatis: sapaan, kode order, nama produk, jumlah, total.
  - Customer melampirkan foto bukti transfer secara manual di WhatsApp.

### 3.2 Sisi Admin

- **FR-A1 — Autentikasi Admin**
  - Login dengan email + password. Hanya admin yang bisa akses dashboard.
  - Logout.

- **FR-A2 — Dashboard Penjualan**
  - Ringkasan: total pesanan, total pendapatan (yang sudah `paid`), pesanan pending, produk terlaris.
  - Daftar pesanan terbaru.

- **FR-A3 — Manajemen Pesanan**
  - Melihat daftar semua pesanan (filter berdasarkan status: pending / paid / cancelled).
  - Melihat detail pesanan (data customer, produk, jumlah, total, waktu).
  - Mengubah status pesanan: `pending` → `paid` (konfirmasi manual setelah cek bukti) atau `cancelled`.

- **FR-A4 — CRUD Produk**
  - Create, Read, Update, Delete produk.
  - Field: nama, slug, deskripsi, harga, durasi, stok, gambar/logo, status aktif/nonaktif.

- **FR-A5 — Pengaturan (Settings)**
  - Nama situs, nomor WhatsApp admin, gambar QRIS statis, nama merchant/rekening, teks instruksi pembayaran, kontak/footer.

## 4. Non-Functional Requirements

- **NFR-1 — Kesederhanaan:** Alur beli maksimal 3 langkah (pilih → checkout → bayar & kirim WA).
- **NFR-2 — Responsif:** Tampil baik di mobile & desktop (mayoritas customer via HP).
- **NFR-3 — Keamanan:** Password admin di-hash; area admin dilindungi middleware auth; validasi input; CSRF protection (bawaan Laravel).
- **NFR-4 — Performa:** Ringan, tanpa dependency berat. SQLite cukup untuk skala kecil–menengah.
- **NFR-5 — Konsistensi visual:** Palet biru muda langit, komponen Bootstrap standar.
- **NFR-6 — Portabilitas:** Mudah dijalankan lokal (SQLite file-based, tanpa server DB terpisah).

## 5. Batasan & Asumsi

- Tidak ada payment gateway otomatis; pembayaran diverifikasi manual via bukti WA.
- QRIS bersifat **statis** (satu gambar untuk semua transaksi) — customer input nominal sendiri sesuai total.
- Tidak ada pengiriman kredensial akun otomatis; penyerahan akun dilakukan admin via WhatsApp setelah pembayaran dikonfirmasi.
- Satu peran admin (belum ada multi-role/staff) pada versi awal.
- Belum ada notifikasi email otomatis pada versi awal.

## 6. Out of Scope (versi awal)

- Integrasi payment gateway (Midtrans/Xendit).
- Registrasi/login customer & riwayat pesanan pribadi.
- Pengiriman kredensial akun otomatis.
- Multi-bahasa, multi-currency.
- Sistem review/rating produk.
