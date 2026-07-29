<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Label Durasi <span class="text-danger">*</span></label>
        <input type="text" name="label" value="{{ old('label', $option->label) }}"
               class="form-control @error('label') is-invalid @enderror" placeholder="1 Bulan" required>
        @error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Urutan</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $option->sort_order ?? 0) }}" min="0"
               class="form-control @error('sort_order') is-invalid @enderror">
        @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
        <input type="number" name="price" value="{{ old('price', $option->price) }}" min="0"
               class="form-control @error('price') is-invalid @enderror" required>
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Stok <span class="text-danger">*</span></label>
        <input type="number" name="stock" value="{{ old('stock', $option->stock) }}" min="0"
               class="form-control @error('stock') is-invalid @enderror" required>
        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <div class="form-check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input"
                   {{ old('is_active', $option->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Tampilkan di katalog (aktif)</label>
        </div>
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Simpan</button>
    <a href="{{ route('admin.variants.options.index', $option->variant ?? $variant) }}" class="btn btn-outline-secondary">Batal</a>
</div>
