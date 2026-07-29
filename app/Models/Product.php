<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /** Produk aktif & masih tampil di katalog. */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Ada minimal satu kombinasi jenis+durasi aktif dengan stok. */
    public function getHasAvailableStockAttribute(): bool
    {
        return $this->variants()
            ->where('is_active', true)
            ->whereHas('options', fn ($q) => $q->where('is_active', true)->where('stock', '>', 0))
            ->exists();
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
