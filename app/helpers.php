<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    /** Baca nilai pengaturan situs. */
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('rupiah')) {
    /** Format angka menjadi Rupiah, contoh: 25000 -> "Rp 25.000". */
    function rupiah($number): string
    {
        return 'Rp ' . number_format((int) $number, 0, ',', '.');
    }
}

if (! function_exists('wa_link')) {
    /** Bangun link wa.me dengan pesan ter-encode. */
    function wa_link(?string $number, string $message = ''): string
    {
        $number = preg_replace('/\D/', '', (string) $number);
        // Normalisasi nomor: 0xxxx -> 62xxxx
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        return 'https://wa.me/' . $number . ($message !== '' ? '?text=' . rawurlencode($message) : '');
    }
}
