@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
    <div class="hero p-4 p-md-5 mb-4 text-center">
        <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" width="56" height="56" class="mb-2">
        <h1 class="fw-bold" style="color: var(--sky-dark);">
            {{ config('app.name') }}
        </h1>
        <p class="text-muted mb-0">Akun premium sharing murah, cepat, dan bergaransi. Pilih, bayar via QRIS, kirim bukti ke WhatsApp!</p>
    </div>

    @if ($products->isEmpty())
        <div class="text-center text-muted py-5">
            <i class="bi bi-box display-4"></i>
            <p class="mt-3">Belum ada produk tersedia saat ini.</p>
        </div>
    @else
        <div class="row g-3 g-md-4">
            @foreach ($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card product-card h-100">
                        @if ($product->image_url)
                            <img src="{{ $product->image_url }}" class="product-thumb" alt="{{ $product->name }}">
                        @else
                            <div class="product-thumb-placeholder">{{ Str::substr($product->name, 0, 1) }}</div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title fw-bold mb-2">{{ $product->name }}</h6>
                            <div class="mb-2">
                                @if ($product->has_available_stock)
                                    <span class="badge text-bg-success">Tersedia</span>
                                @else
                                    <span class="badge text-bg-danger">Stok Habis</span>
                                @endif
                            </div>
                            <a href="{{ route('catalog.show', $product) }}" class="btn btn-primary btn-sm mt-auto">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
