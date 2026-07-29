@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
    <a href="{{ route('admin.orders.index') }}" class="btn btn-link ps-0 mb-2 text-decoration-none">
        <i class="bi bi-arrow-left"></i> Kembali ke Pesanan
    </a>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">{{ $order->order_code }}</h5>
                        <span class="badge text-bg-{{ $order->status_color }} badge-status">{{ $order->status_label }}</span>
                    </div>
                    <table class="table table-sm">
                        <tr><td class="text-muted" width="40%">Tanggal</td><td>{{ $order->created_at->format('d/m/Y H:i') }}</td></tr>
                        <tr><td class="text-muted">Produk</td><td>{{ $order->product_name ?? '-' }}</td></tr>
                        <tr><td class="text-muted">Jenis</td><td>{{ $order->variant_name ?? '-' }}</td></tr>
                        <tr><td class="text-muted">Durasi</td><td>{{ $order->duration_label ?? '-' }}</td></tr>
                        <tr><td class="text-muted">Harga Satuan</td><td>{{ rupiah($order->unit_price) }}</td></tr>
                        <tr><td class="text-muted">Jumlah</td><td>{{ $order->quantity }}</td></tr>
                        <tr><td class="text-muted">Total</td><td class="fw-bold price-tag">{{ rupiah($order->total_price) }}</td></tr>
                    </table>

                    <h6 class="fw-bold mt-3">Data Customer</h6>
                    <table class="table table-sm">
                        <tr><td class="text-muted" width="40%">Nama</td><td>{{ $order->customer_name }}</td></tr>
                        <tr>
                            <td class="text-muted">WhatsApp</td>
                            <td>
                                {{ $order->customer_whatsapp }}
                                <a href="{{ wa_link($order->customer_whatsapp) }}" target="_blank" class="btn btn-sm text-white ms-2" style="background:#25D366;">
                                    <i class="bi bi-whatsapp"></i> Chat
                                </a>
                            </td>
                        </tr>
                        <tr><td class="text-muted">Email</td><td>{{ $order->customer_email ?: '-' }}</td></tr>
                    </table>

                    @if ($order->note)
                        <h6 class="fw-bold mt-3">Catatan</h6>
                        <p class="text-muted" style="white-space: pre-line;">{{ $order->note }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Ubah Status</h6>
                    <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="pending" @selected($order->status === 'pending')>Menunggu Pembayaran</option>
                                <option value="waiting" @selected($order->status === 'waiting')>Menunggu Konfirmasi (stok tertahan)</option>
                                <option value="paid" @selected($order->status === 'paid')>Lunas</option>
                                <option value="cancelled" @selected($order->status === 'cancelled')>Dibatalkan (kembalikan stok)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan <span class="text-muted small">(opsional)</span></label>
                            <textarea name="note" rows="3" class="form-control">{{ $order->note }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Simpan Perubahan
                        </button>
                    </form>
                    <div class="alert alert-light border mt-3 small mb-0">
                        <i class="bi bi-info-circle"></i> Stok otomatis berkurang saat customer menekan <b>Sudah Bayar</b> (status <b>Menunggu Konfirmasi</b>) dan tetap tertahan saat <b>Lunas</b>. Stok dikembalikan bila status diubah ke <b>Dibatalkan</b> atau <b>Menunggu Pembayaran</b>.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
