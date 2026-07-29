<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductVariantOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /** Simpan pesanan baru & arahkan ke halaman pembayaran. */
    public function store(Request $request)
    {
        $option = ProductVariantOption::active()
            ->whereHas('variant', fn ($q) => $q->where('is_active', true)
                ->whereHas('product', fn ($q) => $q->where('is_active', true)))
            ->with('variant.product')
            ->findOrFail($request->input('option_id'));

        $validated = $request->validate([
            'option_id' => ['required', 'exists:product_variant_options,id'],
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_whatsapp' => ['required', 'string', 'max:20', 'regex:/^[0-9+]{8,20}$/'],
            'customer_email' => ['nullable', 'email', 'max:150'],
            'quantity' => ['required', 'integer', 'min:1', 'max:' . max($option->stock, 1)],
        ], [
            'customer_whatsapp.regex' => 'Nomor WhatsApp hanya boleh berisi angka (8–20 digit).',
            'quantity.max' => 'Jumlah melebihi stok yang tersedia.',
        ]);

        if (! $option->is_in_stock) {
            return back()->with('error', 'Maaf, stok pilihan ini sedang habis.');
        }

        $variant = $option->variant;
        $product = $variant->product;

        $order = Order::create([
            'order_code' => Order::generateCode(),
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'product_variant_option_id' => $option->id,
            'product_name' => $product->name,
            'variant_name' => $variant->name,
            'duration_label' => $option->label,
            'customer_name' => $validated['customer_name'],
            'customer_whatsapp' => $validated['customer_whatsapp'],
            'customer_email' => $validated['customer_email'] ?? null,
            'quantity' => $validated['quantity'],
            'unit_price' => $option->price,
            'total_price' => $option->price * $validated['quantity'],
            'status' => Order::STATUS_PENDING,
        ]);

        return redirect()->route('payment.show', $order);
    }

    /** Halaman pembayaran QRIS. */
    public function payment(Order $order)
    {
        return view('catalog.payment', compact('order'));
    }

    /**
     * Customer menekan tombol "Sudah Bayar".
     * Stok langsung dikurangi, status menjadi "menunggu konfirmasi",
     * lalu diarahkan ke WhatsApp admin untuk mengirim bukti transfer.
     */
    public function confirm(Order $order)
    {
        // Hanya proses sekali; klik ulang tidak mengurangi stok lagi (idempoten).
        if ($order->status === Order::STATUS_PENDING) {
            DB::transaction(function () use ($order) {
                $order->reserveStock();
                $order->status = Order::STATUS_WAITING;
                $order->save();
            });
        }

        $productLabel = $order->product_name ?? '-';
        if ($order->variant_name) {
            $productLabel .= ' - ' . $order->variant_name;
        }
        if ($order->duration_label) {
            $productLabel .= ' (' . $order->duration_label . ')';
        }

        $waMessage = "Halo, saya sudah melakukan pembayaran.\n"
            . "Kode Order: {$order->order_code}\n"
            . 'Produk: ' . $productLabel . "\n"
            . "Jumlah: {$order->quantity}\n"
            . 'Total: ' . rupiah($order->total_price) . "\n"
            . 'Berikut saya lampirkan bukti transfernya.';

        return redirect()->away(wa_link(setting('whatsapp_number'), $waMessage));
    }
}
