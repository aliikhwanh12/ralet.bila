<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}"
               class="form-control @error('name') is-invalid @enderror" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-8">
        <label class="form-label">Gambar / Logo <span class="text-muted small">(maks 2MB)</span></label>
        <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if ($product->image_url)
            <img src="{{ $product->image_url }}" class="mt-2 rounded" width="90" height="90" style="object-fit:cover;">
        @endif
    </div>

    <div class="col-md-4 d-flex align-items-center">
        <div class="form-check mt-3">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input"
                   {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Tampilkan di katalog (aktif)</label>
        </div>
    </div>
</div>

@if ($product->exists)
    <div class="alert alert-light border small mt-4 mb-0">
        <i class="bi bi-info-circle"></i> Harga, stok, jenis, dan durasi diatur lewat menu
        <a href="{{ route('admin.products.variants.index', $product) }}">Kelola Jenis</a> setelah produk disimpan.
    </div>
@endif

<div class="mt-4">
    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Simpan</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Batal</a>
</div>
