<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'whatsapp_number' => ['required', 'string', 'max:20', 'regex:/^[0-9+]{8,20}$/'],
            'merchant_name' => ['nullable', 'string', 'max:100'],
            'payment_instruction' => ['nullable', 'string', 'max:1000'],
            'footer_text' => ['nullable', 'string', 'max:255'],
            'qris_image' => ['nullable', 'image', 'max:2048'],
        ], [
            'whatsapp_number.regex' => 'Nomor WhatsApp hanya boleh berisi angka (8–20 digit).',
        ]);

        foreach (['whatsapp_number', 'merchant_name', 'payment_instruction', 'footer_text'] as $key) {
            Setting::put($key, $validated[$key] ?? null);
        }

        if ($request->hasFile('qris_image')) {
            $old = Setting::get('qris_image');
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            Setting::put('qris_image', $request->file('qris_image')->store('qris', 'public'));
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
