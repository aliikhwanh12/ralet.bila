<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $orders = Order::query()
            ->when(in_array($status, [Order::STATUS_PENDING, Order::STATUS_WAITING, Order::STATUS_PAID, Order::STATUS_CANCELLED]),
                fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,waiting,paid,cancelled'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $newStatus = $validated['status'];

        DB::transaction(function () use ($order, $newStatus, $validated) {
            $order->status = $newStatus;

            // Status "menunggu konfirmasi" & "lunas" menahan stok; selain itu stok dikembalikan.
            // reserveStock()/releaseStock() bersifat idempoten sehingga aman untuk semua transisi.
            if (in_array($newStatus, Order::RESERVED_STATUSES, true)) {
                $order->reserveStock();
            } else {
                $order->releaseStock();
            }

            $order->note = $validated['note'] ?? $order->note;
            $order->save();
        });

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
