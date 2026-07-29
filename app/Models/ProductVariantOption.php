<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariantOption extends Model
{
    protected $fillable = [
        'product_variant_id',
        'label',
        'price',
        'stock',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->stock > 0;
    }
}
