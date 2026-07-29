<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Order extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_WAITING = 'waiting';
    public const STATUS_PAID = 'paid';
    public const STATUS_CANCELLED = 'cancelled';

    /** Status yang menahan (mengurangi) stok produk. */
    public const RESERVED_STATUSES = [self::STATUS_WAITING, self::STATUS_PAID];

    protected $fillable = [
        'order_code',
        'product_id',
        'product_variant_id',
        'product_variant_option_id',
        'product_name',
        'variant_name',
        'duration_label',
        'customer_name',
        'customer_whatsapp',
        'customer_email',
        'quantity',
        'unit_price',
        'total_price',
        'status',
        'stock_reduced',
        'note',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'integer',
        'total_price' => 'integer',
        'stock_reduced' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function variantOption(): BelongsTo
    {
        return $this->belongsTo(ProductVariantOption::class, 'product_variant_option_id');
    }

    public function getRouteKeyName(): string
    {
        return 'order_code';
    }

    /** Generate kode order unik: INV-YYYYMMDD-XXXX */
    public static function generateCode(): string
    {
        do {
            $code = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (static::where('order_code', $code)->exists());

        return $code;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PAID => 'Lunas',
            self::STATUS_WAITING => 'Menunggu Konfirmasi',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => 'Menunggu Pembayaran',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PAID => 'success',
            self::STATUS_WAITING => 'info',
            self::STATUS_CANCELLED => 'danger',
            default => 'warning',
        };
    }

    /**
     * Kurangi stok opsi durasi untuk pesanan ini (idempoten).
     * Mengubah atribut in-memory; pemanggil bertanggung jawab menyimpan ($this->save()).
     * Panggil di dalam DB::transaction agar lock opsi berlaku.
     */
    public function reserveStock(): void
    {
        if ($this->stock_reduced || ! $this->product_variant_option_id) {
            return;
        }

        $option = $this->variantOption()->lockForUpdate()->first();
        if ($option) {
            $option->decrement('stock', $this->quantity);
            $this->stock_reduced = true;
        }
    }

    /**
     * Kembalikan stok opsi durasi yang sebelumnya dikurangi (idempoten).
     * Mengubah atribut in-memory; pemanggil bertanggung jawab menyimpan ($this->save()).
     */
    public function releaseStock(): void
    {
        if (! $this->stock_reduced) {
            return;
        }

        if ($this->product_variant_option_id) {
            $this->variantOption()->lockForUpdate()->first()?->increment('stock', $this->quantity);
        }

        $this->stock_reduced = false;
    }
}
