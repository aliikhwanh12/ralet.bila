# Website Pembelian Akun Premium Sharing

Website sederhana untuk menjual akun premium sharing (Netflix, Spotify, dll). Customer memilih **aplikasi** → pilih **jenis** & **durasi** → bayar via **QRIS statis** → kirim bukti ke **WhatsApp** admin. Admin mengelola katalog (aplikasi/jenis/durasi), pesanan, dan pengaturan situs.

Dibuat dengan **Laravel 13 + Bootstrap 5 + SQLite**. Tema warna biru muda langit. Nama situs & logo bersifat **statis** (diatur lewat `.env` & file gambar, bukan dari database).

## Fitur

**Customer (tanpa login)**
- Katalog aplikasi (mis. Netflix, Spotify) beserta status stok
- Halaman detail aplikasi: pilihan dikelompokkan per **Jenis** (mis. Sharing/Private), masing-masing menampilkan daftar **Durasi** + harga + tombol **Beli** langsung ke checkout kombinasi tersebut
- Checkout (nama, WhatsApp, email opsional, jumlah)
- Halaman pembayaran QRIS statis + tombol "Sudah Bayar — Kirim Bukti via WhatsApp" (pesan otomatis berisi kode order, produk, jenis, durasi, dan total). Menekan tombol ini **langsung mengurangi stok** & menandai pesanan **Menunggu Konfirmasi**, lalu mengarahkan ke WhatsApp admin

**Admin**
- Login / logout
- Dashboard penjualan (total pesanan, pendapatan, perlu konfirmasi, menunggu bayar, produk aktif, produk terlaris)
- Kelola pesanan + ubah status. Stok berkurang saat customer menekan **Sudah Bayar** (status *Menunggu Konfirmasi*) dan tetap tertahan saat **Lunas**; stok dikembalikan bila status diubah ke **Dibatalkan** atau **Menunggu Pembayaran**. Pengurangan/pengembalian stok idempoten (tidak pernah dobel)
- CRUD **Aplikasi** (nama, deskripsi, gambar, aktif/nonaktif, auto-slug)
- CRUD **Jenis** per aplikasi (mis. Sharing, Private) — menu "Kelola Jenis"
- CRUD **Durasi** per jenis (label, harga, stok, aktif/nonaktif) — menu "Kelola Durasi". Harga & stok sepenuhnya ditentukan di level ini, bukan di level aplikasi
- Pengaturan: nomor WhatsApp, gambar QRIS, nama merchant (tampil di halaman QRIS), instruksi pembayaran, teks footer

> Nama situs & logo **tidak** diatur dari halaman Pengaturan — lihat bagian [Kustomisasi Nama & Logo](#kustomisasi-nama--logo) di bawah.

## Struktur Katalog

```
Aplikasi (Product)         mis. "Netflix Premium"
 └─ Jenis (ProductVariant)  mis. "Sharing", "Private"
     └─ Durasi (ProductVariantOption)   mis. "1 Bulan" — punya harga & stok sendiri
```

Setiap kombinasi Jenis+Durasi adalah baris tersendiri dengan harga & stok independen, jadi satu aplikasi bisa punya banyak pilihan tanpa perlu membuat produk duplikat. Saat pesanan dibuat, nama aplikasi/jenis/durasi **disalin (snapshot)** ke tabel `orders` supaya riwayat pesanan tetap utuh meskipun admin kelak mengubah/menghapus jenis atau durasi tersebut.

## Persyaratan
- PHP 8.3+
- Composer

## Instalasi

```bash
# 1. Install dependency
composer install

# 2. Salin env & generate key (jika .env belum ada)
cp .env.example .env
php artisan key:generate

# 3. Siapkan database SQLite + data awal
php artisan migrate:fresh --seed

# 4. Symlink storage (agar gambar tampil)
php artisan storage:link

# 5. Jalankan
php artisan serve
```

Buka: <http://127.0.0.1:8000>

## Kredensial Admin Default

| | |
|---|---|
| URL | `/admin/login` |
| Email | `admin@admin.com` |
| Password | `password` |

> **Penting:** ganti password admin & nomor WhatsApp di menu Pengaturan setelah instalasi. Upload gambar QRIS Anda sendiri di menu Pengaturan agar halaman pembayaran menampilkan QRIS.

## Kustomisasi Nama & Logo

Nama situs diambil dari `APP_NAME` di file `.env` (dipakai lewat `config('app.name')` di semua halaman — judul tab, navbar, sidebar admin, halaman login, footer). Untuk mengganti nama situs, ubah baris berikut lalu jalankan `php artisan config:clear` bila perlu:

```env
APP_NAME="Nama Toko Anda"
```

Logo adalah file gambar statis di `public/images/logo.png`. Untuk mengganti logo, cukup timpa file tersebut dengan gambar Anda sendiri (disarankan persegi, latar transparan). File ini juga dipakai sebagai favicon di seluruh halaman.

## Alur Pesanan & Status

```
pending (Menunggu Pembayaran)
   │  customer klik "Sudah Bayar" → stok berkurang
   ▼
waiting (Menunggu Konfirmasi)  ──┐
   │  admin ubah status ke Lunas  │  admin ubah status ke Dibatalkan/Menunggu Pembayaran
   ▼                              │  → stok dikembalikan
paid (Lunas) ─────────────────────┘
   │
cancelled (Dibatalkan)
```

Logika penambahan/pengembalian stok ada di `App\Models\Order::reserveStock()` / `releaseStock()`, memakai `lockForUpdate()` + `DB::transaction` + flag `stock_reduced` supaya idempoten (tidak pernah mengurangi/mengembalikan stok dua kali untuk pesanan yang sama).

## Struktur Ringkas

- `app/Models/`
  - `Product` — Aplikasi (`name, slug, description, image, is_active`)
  - `ProductVariant` — Jenis, milik satu Product (`name, is_active, sort_order`)
  - `ProductVariantOption` — Durasi, milik satu Variant (`label, price, stock, is_active, sort_order`)
  - `Order` — pesanan, menyimpan FK ke ketiga level + snapshot nama (`product_name, variant_name, duration_label`) + harga (`unit_price, total_price`)
  - `Setting` — pengaturan key/value (WhatsApp, QRIS, instruksi, footer)
- `app/Http/Controllers/`
  - `CatalogController`, `OrderController` — alur customer
  - `Admin/ProductController`, `Admin/ProductVariantController`, `Admin/ProductVariantOptionController` — CRUD katalog 3 level
  - `Admin/OrderController`, `Admin/DashboardController`, `Admin/SettingController`, `Admin/AuthController`
- `app/helpers.php` — `setting()`, `rupiah()`, `wa_link()`
- `resources/views/`
  - `catalog/*` — katalog & checkout customer
  - `admin/products/*`, `admin/variants/*`, `admin/options/*` — CRUD katalog bertingkat
  - `admin/orders/*`, `admin/dashboard.blade.php`, `admin/settings/*`
  - `layouts/app.blade.php` (customer), `layouts/admin.blade.php` (admin)
- `public/css/app.css` — tema biru muda langit
- `public/images/logo.png` — logo statis situs (lihat [Kustomisasi Nama & Logo](#kustomisasi-nama--logo))
- `database/seeders/DatabaseSeeder.php` — admin, pengaturan default, 6 aplikasi contoh (masing-masing 1 jenis "Sharing" + 1 durasi)

## Rute Utama

**Customer**
| Method | URL | Nama Route |
|---|---|---|
| GET | `/` | `catalog.index` |
| GET | `/produk/{product}` | `catalog.show` |
| GET | `/checkout/{option}` | `catalog.checkout` |
| POST | `/order` | `order.store` |
| GET | `/pembayaran/{order}` | `payment.show` |
| POST | `/pembayaran/{order}/konfirmasi` | `payment.confirm` |

**Admin** (prefix `/admin`, butuh login kecuali disebutkan)
| Method | URL | Nama Route |
|---|---|---|
| GET/POST | `/admin/login` | `admin.login`, `admin.login.attempt` |
| GET | `/admin` | `admin.dashboard` |
| GET | `/admin/orders`, `/admin/orders/{order}` | `admin.orders.index`, `admin.orders.show` |
| PATCH | `/admin/orders/{order}/status` | `admin.orders.status` |
| resource | `/admin/products` | `admin.products.*` |
| resource (shallow) | `/admin/products/{product}/variants`, `/admin/variants/{variant}` | `admin.products.variants.*`, `admin.variants.*` |
| resource (shallow) | `/admin/variants/{variant}/options`, `/admin/options/{option}` | `admin.variants.options.*`, `admin.options.*` |
| GET/PUT | `/admin/settings` | `admin.settings.edit`, `admin.settings.update` |
