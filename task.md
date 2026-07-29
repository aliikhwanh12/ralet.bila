# Task — Website Pembelian Akun Premium Sharing

Checklist implementasi berurutan. Tandai `[x]` bila selesai.

## Fase 0 — Setup Proyek
- [ ] Buat proyek Laravel baru (`composer create-project laravel/laravel .` atau installer)
- [ ] Konfigurasi `.env` untuk SQLite (`DB_CONNECTION=sqlite`, buat file `database/database.sqlite`)
- [ ] Pasang Bootstrap 5 (CDN atau via npm/vite) + setup file CSS variabel warna sky blue
- [ ] Jalankan `php artisan storage:link`
- [ ] Verifikasi `php artisan serve` berjalan

## Fase 1 — Database & Model
- [ ] Migration `products` (name, slug, description, price, duration, stock, image, is_active)
- [ ] Migration `orders` (order_code, product_id, customer_name, customer_whatsapp, customer_email, quantity, unit_price, total_price, status, note)
- [ ] Migration `settings` (key, value)
- [ ] Model `Product` (fillable, relasi hasMany orders, scope active)
- [ ] Model `Order` (fillable, relasi belongsTo product, casting status)
- [ ] Model `Setting` + helper global `setting()`
- [ ] Seeder: admin default (users), beberapa produk contoh, settings default (WA, QRIS, dll)
- [ ] `php artisan migrate --seed`

## Fase 2 — Layout & Styling
- [ ] Layout `layouts/app.blade.php` (navbar + footer customer)
- [ ] Layout `layouts/admin.blade.php` (sidebar admin)
- [ ] File CSS: palet biru muda langit, override `$primary` Bootstrap, styling card/tombol/badge
- [ ] Komponen badge status (pending/paid/cancelled)

## Fase 3 — Sisi Customer
- [ ] Controller `CatalogController` — katalog (`/`) menampilkan produk aktif
- [ ] Halaman katalog: grid product card responsif
- [ ] Halaman detail produk (`/produk/{slug}`)
- [ ] Halaman checkout (`/checkout/{slug}`) + form
- [ ] Controller `OrderController@store` — validasi, buat order + `order_code`, snapshot harga
- [ ] Halaman pembayaran (`/pembayaran/{kode}`): tampil QRIS statis, total, kode order
- [ ] Tombol "Sudah Bayar — Kirim Bukti via WhatsApp" dengan link `wa.me` + pesan pre-filled

## Fase 4 — Autentikasi Admin
- [ ] Route & controller login/logout admin (`/admin/login`)
- [ ] Middleware `auth` pada grup route `/admin`
- [ ] Halaman login sederhana (bertema sky blue)
- [ ] Redirect ke dashboard setelah login

## Fase 5 — Dashboard & Pesanan (Admin)
- [ ] Controller `Admin\DashboardController` — hitung total order, pendapatan (paid), pending, produk terlaris
- [ ] Halaman dashboard: stat cards + daftar pesanan terbaru
- [ ] Controller `Admin\OrderController` — index (filter status), show, updateStatus
- [ ] Halaman daftar pesanan + filter
- [ ] Halaman detail pesanan
- [ ] Aksi ubah status (pending → paid/cancelled), kurangi stok saat paid

## Fase 6 — CRUD Produk (Admin)
- [ ] Controller `Admin\ProductController` (resource)
- [ ] Halaman daftar produk + tombol aksi
- [ ] Form create produk (upload gambar)
- [ ] Form edit produk
- [ ] Hapus produk (konfirmasi)
- [ ] Toggle is_active

## Fase 7 — Pengaturan (Admin)
- [ ] Controller `Admin\SettingController` (edit + update)
- [ ] Form settings: nama situs, nomor WhatsApp, upload QRIS, nama merchant, instruksi pembayaran, footer
- [ ] Simpan settings (key-value) + upload QRIS

## Fase 8 — Finishing & QA
- [ ] Uji alur beli end-to-end (katalog → bayar → WA)
- [ ] Uji CRUD produk & perubahan status pesanan
- [ ] Uji validasi form (input salah)
- [ ] Cek responsif di mobile
- [ ] Format Rupiah konsisten (helper `rupiah()`)
- [ ] Empty state (belum ada produk / pesanan)
- [ ] Pesan flash sukses/gagal
- [ ] README singkat: cara instalasi & kredensial admin default

## Catatan Keputusan (perlu konfirmasi saat implementasi)
- Pengurangan stok: saat status `paid` (default) atau saat order dibuat?
- QRIS statis: customer input nominal manual sesuai total (dikonfirmasi).
- Laravel versi berapa yang terpasang di mesin (menentukan sintaks routing/struktur).
