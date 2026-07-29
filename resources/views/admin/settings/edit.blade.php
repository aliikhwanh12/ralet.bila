@extends('layouts.admin')

@section('title', 'Pengaturan')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <div class="alert alert-light border small mb-4">
                        <i class="bi bi-info-circle"></i> Nama situs & logo sekarang statis (<strong>{{ config('app.name') }}</strong>), diatur lewat <code>APP_NAME</code> di <code>.env</code> dan <code>public/images/logo.png</code> — tidak lagi bisa diubah dari sini.
                    </div>
                    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nomor WhatsApp Admin <span class="text-danger">*</span></label>
                            <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', setting('whatsapp_number')) }}"
                                   class="form-control @error('whatsapp_number') is-invalid @enderror" placeholder="6281234567890" required>
                            <div class="form-text">Format internasional tanpa tanda + (contoh: 6281234567890). Ke sinilah bukti transaksi dikirim.</div>
                            @error('whatsapp_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Merchant (di halaman QRIS)</label>
                            <input type="text" name="merchant_name" value="{{ old('merchant_name', setting('merchant_name')) }}"
                                   class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gambar QRIS Statis</label>
                            <input type="file" name="qris_image" accept="image/*"
                                   class="form-control @error('qris_image') is-invalid @enderror">
                            @error('qris_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if (setting('qris_image'))
                                <img src="{{ asset('storage/' . setting('qris_image')) }}" class="mt-2 rounded border p-2" width="160">
                            @else
                                <div class="form-text text-warning"><i class="bi bi-exclamation-triangle"></i> QRIS belum di-upload.</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Instruksi Pembayaran</label>
                            <textarea name="payment_instruction" rows="4" class="form-control">{{ old('payment_instruction', setting('payment_instruction')) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Teks Footer</label>
                            <input type="text" name="footer_text" value="{{ old('footer_text', setting('footer_text')) }}"
                                   class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Simpan Pengaturan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
