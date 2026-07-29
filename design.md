# Design — Website Pembelian Akun Premium Sharing

## 1. Arsitektur Umum

Aplikasi **Laravel monolith** dengan Blade server-rendered views. Tidak ada SPA/API terpisah pada versi awal.

```
Browser (Customer / Admin)
        │
        ▼
   Laravel (Routes → Controllers → Views Blade)
        │
        ├── Middleware auth (area admin)
        ▼
   Eloquent Models  ──►  SQLite (database.sqlite)
        │
        └── Storage (public/storage: gambar produk & QRIS)
```

- **Frontend:** Bootstrap 5 (via CDN atau npm), Blade layout master.
- **Backend:** Laravel 11 (atau versi LTS terpasang), Eloquent ORM.
- **DB:** SQLite (`database/database.sqlite`).
- **Auth admin:** Laravel bawaan (session-based), seeder membuat 1 akun admin default.

## 2. Palet Warna (Biru Muda Langit)

| Token | Hex | Penggunaan |
|-------|-----|-----------|
| `--sky-primary` | `#4FB8E8` | Warna utama tombol, link, aksen |
| `--sky-light` | `#E7F5FC` | Background section / card hover |
| `--sky-lighter` | `#F5FBFE` | Background halaman |
| `--sky-dark` | `#2E8BC0` | Hover tombol, heading |
| `--sky-accent` | `#87CEEB` | Badge, border aksen |
| `--text-main` | `#1F2D3D` | Teks utama |
| `--text-muted` | `#6B7A90` | Teks sekunder |
| `--success` | `#28a745` | Status paid |
| `--warning` | `#ffc107` | Status pending |
| `--danger` | `#dc3545` | Status cancelled |

Diterapkan lewat CSS variables di `resources/css/app.css` dan override sebagian variabel Bootstrap (`$primary`).

## 3. Struktur Halaman & Routing

### 3.1 Customer (public)

| Route | Method | Halaman | Keterangan |
|-------|--------|---------|-----------|
| `/` | GET | Katalog produk | Grid card produk aktif |
| `/produk/{slug}` | GET | Detail produk | Detail + tombol beli |
| `/checkout/{slug}` | GET | Form checkout | Isi nama, WA, jumlah |
| `/order` | POST | Proses order | Buat order, redirect ke pembayaran |
| `/pembayaran/{kode}` | GET | Halaman QRIS | Tampil QRIS + tombol WA |

### 3.2 Admin (prefix `/admin`, middleware `auth`)

| Route | Method | Halaman |
|-------|--------|---------|
| `/admin/login` | GET/POST | Login |
| `/admin/logout` | POST | Logout |
| `/admin` (dashboard) | GET | Ringkasan penjualan |
| `/admin/orders` | GET | Daftar pesanan |
| `/admin/orders/{id}` | GET | Detail pesanan |
| `/admin/orders/{id}/status` | PATCH | Ubah status pesanan |
| `/admin/products` | GET | Daftar produk |
| `/admin/products/create` | GET | Form tambah |
| `/admin/products` | POST | Simpan produk |
| `/admin/products/{id}/edit` | GET | Form edit |
| `/admin/products/{id}` | PUT/PATCH | Update produk |
| `/admin/products/{id}` | DELETE | Hapus produk |
| `/admin/settings` | GET | Form pengaturan |
| `/admin/settings` | PUT | Simpan pengaturan |

## 4. Skema Database

### 4.1 `products`
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | bigint PK | |
| name | string | Nama produk |
| slug | string unique | URL-friendly |
| description | text nullable | Deskripsi |
| price | integer | Harga (Rupiah, tanpa desimal) |
| duration | string nullable | Contoh: "1 Bulan", "3 Bulan" |
| stock | integer default 0 | Stok tersedia (0 = habis) |
| image | string nullable | Path gambar |
| is_active | boolean default true | Tampil/tidak |
| timestamps | | |

### 4.2 `orders`
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | bigint PK | |
| order_code | string unique | Contoh: `INV-20260723-A1B2` |
| product_id | FK → products | |
| customer_name | string | |
| customer_whatsapp | string | |
| customer_email | string nullable | |
| quantity | integer default 1 | |
| unit_price | integer | Harga saat order (snapshot) |
| total_price | integer | quantity × unit_price |
| status | enum(`pending`,`paid`,`cancelled`) default `pending` | |
| note | text nullable | Catatan admin |
| timestamps | | |

### 4.3 `settings` (key-value)
| Kolom | Tipe |
|-------|------|
| id | bigint PK |
| key | string unique |
| value | text nullable |

Key yang dipakai: `whatsapp_number`, `qris_image`, `merchant_name`, `payment_instruction`, `footer_text`. Nama situs & logo bersifat statis (`APP_NAME` di `.env` dan `public/images/logo.svg`), bukan lagi bagian dari Setting.

### 4.4 `users` (admin)
Tabel bawaan Laravel (`name`, `email`, `password`). Diisi via seeder untuk 1 admin default.

### 4.5 Relasi
- `Order` **belongsTo** `Product`.
- `Product` **hasMany** `Order`.

## 5. Model & Logika Kunci

- **Pembuatan `order_code`:** `INV-{YYYYMMDD}-{random 4 char}` di-generate saat create order, dijamin unik.
- **Snapshot harga:** `unit_price` & `total_price` disimpan saat order dibuat agar tidak berubah jika harga produk diedit.
- **Pengurangan stok:** stok dikurangi saat admin mengubah status ke `paid` (bukan saat order dibuat) — menghindari stok "tersandera" order pending. *(Keputusan: konfirmasi saat implementasi bila ingin dikurangi saat order dibuat.)*
- **Settings helper:** helper global `setting('key', $default)` untuk membaca pengaturan; di-cache sederhana per request.

## 6. Alur Utama

### 6.1 Alur Beli (Customer)
```
Katalog (/) → Detail produk → Checkout (isi nama+WA) 
  → POST /order (buat order status=pending) 
  → Halaman Pembayaran (QRIS statis + total + kode order)
  → Klik "Sudah Bayar" → buka wa.me dengan pesan pre-filled
  → Customer lampirkan bukti transfer di WhatsApp
```

Format link WhatsApp:
```
https://wa.me/{whatsapp_number}?text={pesan terenkode}
```
Pesan pre-filled contoh:
```
Halo, saya sudah melakukan pembayaran.
Kode Order: INV-20260723-A1B2
Produk: Netflix Premium 1 Bulan
Jumlah: 1
Total: Rp 25.000
Berikut saya lampirkan bukti transfernya.
```

### 6.2 Alur Konfirmasi (Admin)
```
Admin login → Dashboard → Orders → buka order pending 
  → cek bukti (dari WhatsApp) → ubah status ke "paid" 
  → (stok berkurang) → serahkan akun ke customer via WA
```

## 7. Struktur Layout & Komponen UI

### 7.1 Layout
- `layouts/app.blade.php` — layout customer (navbar simpel: logo/nama situs + link katalog; footer).
- `layouts/admin.blade.php` — layout admin (sidebar: Dashboard, Pesanan, Produk, Pengaturan, Logout).

### 7.2 Komponen Customer
- **Product card:** gambar, nama, durasi, harga, badge stok, tombol "Lihat / Beli".
- **Checkout form:** input nama, WhatsApp, email (opsional), qty, ringkasan harga.
- **Payment card:** QRIS besar di tengah, total menonjol, kode order, tombol WA hijau.

### 7.3 Komponen Admin
- **Stat cards** (dashboard): total order, pendapatan, pending, produk aktif.
- **Tabel** pesanan & produk dengan aksi.
- **Badge status** berwarna (pending=kuning, paid=hijau, cancelled=merah).

## 8. Validasi Utama

- Checkout: `customer_name` required; `customer_whatsapp` required, format nomor; `quantity` min 1 & ≤ stok.
- Produk (admin): `name`, `price` (numeric ≥ 0), `stock` (integer ≥ 0), `image` (mimes/size), `slug` unik.
- Settings: `whatsapp_number` format angka; `qris_image` gambar.

## 9. Keamanan
- Semua route `/admin/*` (kecuali login) dilindungi middleware `auth`.
- CSRF token pada semua form (bawaan Blade `@csrf`).
- Hash password (`bcrypt`).
- Escaping output Blade (`{{ }}`) mencegah XSS.
- File upload disimpan di `storage/app/public`, diakses via `storage:link`.

## 10. Penanganan Media
- Upload gambar produk & QRIS ke `storage/app/public/{products|qris}`.
- `php artisan storage:link` untuk expose ke `public/storage`.
- Tampilkan via `asset('storage/...')`.
