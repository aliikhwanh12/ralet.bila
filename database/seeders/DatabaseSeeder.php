<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== Admin default =====
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );

        // ===== Pengaturan situs default =====
        $settings = [
            'whatsapp_number' => '6281234567890',
            'qris_image' => null,
            'merchant_name' => 'Premium Store',
            'payment_instruction' => "1. Scan QRIS di atas menggunakan aplikasi e-wallet / m-banking.\n2. Masukkan nominal sesuai total pesanan.\n3. Setelah berhasil, klik tombol \"Sudah Bayar\" dan kirim bukti transfer via WhatsApp.",
            'footer_text' => '© ' . date('Y') . ' Premium Store — Akun Premium Sharing Murah & Terpercaya.',
        ];
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // ===== Produk contoh =====
        $products = [
            [
                'name' => 'Netflix Premium',
                'description' => 'Akun Netflix Premium 4K UHD, sharing profil pribadi. Bisa untuk HP, TV, laptop. Garansi penuh selama masa aktif.',
                'price' => 25000,
                'duration' => '1 Bulan',
                'stock' => 15,
            ],
            [
                'name' => 'Spotify Premium',
                'description' => 'Spotify Premium individual, bebas iklan, kualitas audio tinggi, bisa download lagu. Login akun pribadi.',
                'price' => 18000,
                'duration' => '1 Bulan',
                'stock' => 20,
            ],
            [
                'name' => 'YouTube Premium',
                'description' => 'YouTube Premium + YouTube Music, tanpa iklan, bisa play di background & download video.',
                'price' => 15000,
                'duration' => '1 Bulan',
                'stock' => 12,
            ],
            [
                'name' => 'Canva Pro',
                'description' => 'Canva Pro akses penuh: jutaan template, elemen premium, background remover, dan resize.',
                'price' => 20000,
                'duration' => '1 Bulan',
                'stock' => 0,
            ],
            [
                'name' => 'Disney+ Hotstar',
                'description' => 'Disney+ Hotstar Premium, streaming film & series eksklusif kualitas HD.',
                'price' => 22000,
                'duration' => '1 Bulan',
                'stock' => 8,
            ],
            [
                'name' => 'ChatGPT Plus',
                'description' => 'Akses ChatGPT Plus (GPT-4) sharing. Cocok untuk produktivitas & belajar.',
                'price' => 45000,
                'duration' => '1 Bulan',
                'stock' => 5,
            ],
        ];

        foreach ($products as $data) {
            $price = $data['price'];
            $duration = $data['duration'];
            $stock = $data['stock'];
            unset($data['price'], $data['duration'], $data['stock']);

            $data['slug'] = Str::slug($data['name']);
            $data['is_active'] = true;
            $product = Product::updateOrCreate(['slug' => $data['slug']], $data);

            $variant = $product->variants()->updateOrCreate(
                ['name' => 'Sharing'],
                ['is_active' => true, 'sort_order' => 0]
            );

            $variant->options()->updateOrCreate(
                ['label' => $duration],
                ['price' => $price, 'stock' => $stock, 'is_active' => true, 'sort_order' => 0]
            );
        }
    }
}
