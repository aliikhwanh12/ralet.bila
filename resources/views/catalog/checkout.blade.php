@extends('layouts.app')

@section('title', 'Checkout — ' . $option->variant->product->name)

@section('content')
    <a href="{{ route('catalog.show', $option->variant->product) }}" class="btn btn-link ps-0 mb-2 text-decoration-none">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="row g-4 justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">Data Pemesan</h4>
                    <form method="POST" action="{{ route('order.store') }}">
                        @csrf
                        <input type="hidden" name="option_id" value="{{ $option->id }}">

                        <div class="mb-3">
                            <label class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}"
                                   class="form-control @error('customer_name') is-invalid @enderror" required>
                            @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="customer_whatsapp" value="{{ old('customer_whatsapp') }}"
                                   class="form-control @error('customer_whatsapp') is-invalid @enderror"
                                   placeholder="08xxxxxxxxxx" required>
                            <div class="form-text">Contoh: 081234567890</div>
                            @error('customer_whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-muted small">(opsional)</span></label>
                            <input type="email" name="customer_email" value="{{ old('customer_email') }}"
                                   class="form-control @error('customer_email') is-invalid @enderror">
                            @error('customer_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}"
                                   min="1" max="{{ $option->stock }}"
                                   class="form-control @error('quantity') is-invalid @enderror"
                                   data-price="{{ $option->price }}" required>
                            @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-arrow-right-circle"></i> Lanjut ke Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $option->variant->product->name }}</span>
                        <span>{{ rupiah($option->price) }}</span>
                    </div>
                    <div class="text-muted small mb-2">
                        <i class="bi bi-tag"></i> {{ $option->variant->name }}
                        <i class="bi bi-clock ms-2"></i> {{ $option->label }}
                    </div>
                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <span>Jumlah</span>
                        <span id="summary-qty">1</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span class="price-tag" id="summary-total">{{ rupiah($option->price) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const qtyInput = document.getElementById('quantity');
    const price = parseInt(qtyInput.dataset.price);
    const fmt = n => 'Rp ' + n.toLocaleString('id-ID');
    function update() {
        let q = parseInt(qtyInput.value) || 1;
        document.getElementById('summary-qty').textContent = q;
        document.getElementById('summary-total').textContent = fmt(price * q);
    }
    qtyInput.addEventListener('input', update);
    update();
</script>
@endpush
