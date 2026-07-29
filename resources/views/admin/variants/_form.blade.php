<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Nama Jenis <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $variant->name) }}"
               class="form-control @error('name') is-invalid @enderror" placeholder="Sharing / Private" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Urutan</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $variant->sort_order ?? 0) }}" min="0"
               class="form-control @error('sort_order') is-invalid @enderror">
        @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <div class="form-check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input"
                   {{ old('is_active', $variant->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Tampilkan di katalog (aktif)</label>
        </div>
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Simpan</button>
    <a href="{{ route('admin.products.variants.index', $variant->product ?? $product) }}" class="btn btn-outline-secondary">Batal</a>
</div>
