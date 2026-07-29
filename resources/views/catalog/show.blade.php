@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <a href="{{ route('catalog.index') }}" class="btn btn-link ps-0 mb-2 text-decoration-none">
        <i class="bi bi-arrow-left"></i> Kembali ke Katalog
    </a>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="card">
                @if ($product->image_url)
                    <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}" style="max-height:320px;object-fit:cover;">
                @else
                    <div class="product-thumb-placeholder" style="height:260px;font-size:5rem;">{{ Str::substr($product->name, 0, 1) }}</div>
                @endif
            </div>
        </div>
        <div class="col-md-7">
            <h3 class="fw-bold">{{ $product->name }}</h3>

            @if ($product->description)
                <p class="text-muted mb-4" style="white-space: pre-line;">{{ $product->description }}</p>
            @endif

            @if ($product->variants->isEmpty())
                <p class="text-muted"><i class="bi bi-info-circle"></i> Belum ada pilihan tersedia untuk produk ini.</p>
            @else
                @foreach ($product->variants as $variant)
                    <div class="mb-4">
                        <h6 class="fw-bold text-uppercase small" style="color: var(--sky-dark); letter-spacing:.03em;">
                            <i class="bi bi-tag"></i> {{ $variant->name }}
                        </h6>

                        @if ($variant->options->isEmpty())
                            <p class="text-muted small">Belum ada pilihan durasi.</p>
                        @else
                            <div class="list-group">
                                @foreach ($variant->options as $option)
                                    <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <div>
                                            <div class="fw-semibold">{{ $option->label }}</div>
                                            <div class="price-tag">{{ rupiah($option->price) }}</div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($option->is_in_stock)
                                                <span class="badge text-bg-light border">Tersedia ({{ $option->stock }})</span>
                                                <a href="{{ route('catalog.checkout', $option) }}" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-cart-check"></i> Beli
                                                </a>
                                            @else
                                                <span class="badge text-bg-danger">Habis</span>
                                                <button class="btn btn-secondary btn-sm" disabled>Beli</button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
