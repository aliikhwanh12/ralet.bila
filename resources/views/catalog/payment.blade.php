@extends('layouts.app')

@php
    $qris = setting('qris_image');
    $confirmed = $order->status !== \App\Models\Order::STATUS_PENDING;
@endphp

@section('title', 'Pembayaran — ' . $order->order_code)

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center mb-3">
                @if ($confirmed)
                    <span class="badge text-bg-{{ $order->status_color }} badge-status">
                        <i class="bi bi-check2-circle"></i> {{ $order->status_label }}
                    </span>
                @else
                    <span class="badge text-bg-warning badge-status">
                        <i class="bi bi-hourglass-split"></i> Menunggu Pembayaran
                    </span>
                @endif
            </div>

            <div class="card mb-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Kode Order</span>
                        <span class="fw-bold">{{ $order->order_code }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Produk</span>
                        <span class="text-end">
                            {{ $order->product_name ?? '-' }}
                            @if ($order->variant_name || $order->duration_label)
                                <br><span class="text-muted small">{{ $order->variant_name }} @if($order->variant_name && $order->duration_label) &middot; @endif {{ $order->duration_label }}</span>
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Jumlah</span>
                        <span>{{ $order->quantity }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Total Bayar</span>
                        <span class="price-tag fs-4">{{ rupiah($order->total_price) }}</span>
                    </div>
                </div>
            </div>

            <div class="qris-box mb-3">
                <h6 class="fw-bold mb-1">Scan QRIS untuk Membayar</h6>
                <p class="text-muted small mb-3">{{ setting('merchant_name', 'Premium Store') }}</p>
                @if ($qris)
                    <img src="{{ asset('storage/' . $qris) }}" alt="QRIS" class="img-fluid">
                @else
                    <div class="text-muted py-5">
                        <i class="bi bi-qr-code display-3"></i>
                        <p class="mt-2 mb-0 small">QRIS belum diatur oleh admin.</p>
                    </div>
                @endif
            </div>

            @if (setting('payment_instruction'))
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold"><i class="bi bi-info-circle"></i> Cara Pembayaran</h6>
                        <div class="text-muted small" style="white-space: pre-line;">{{ setting('payment_instruction') }}</div>
                    </div>
                </div>
            @endif

            @if ($confirmed)
                <div class="alert alert-info text-center small">
                    <i class="bi bi-info-circle"></i> Pembayaran Anda sedang menunggu konfirmasi admin.
                    Jika belum mengirim bukti, kirim ulang lewat tombol di bawah.
                </div>
            @endif

            <form method="POST" action="{{ route('payment.confirm', $order) }}">
                @csrf
                <button type="submit" class="btn btn-lg w-100 text-white" style="background-color:#25D366;">
                    <i class="bi bi-whatsapp"></i>
                    {{ $confirmed ? 'Kirim Ulang Bukti via WhatsApp' : 'Sudah Bayar — Kirim Bukti via WhatsApp' }}
                </button>
            </form>
            <p class="text-center text-muted small mt-2">
                Jangan lupa lampirkan foto bukti transfer di WhatsApp ya 🙏
            </p>

            <div class="text-center mt-3">
                <a href="{{ route('catalog.index') }}" class="btn btn-link text-decoration-none">
                    <i class="bi bi-house"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
@endsection
