<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public $timestamps = true;

    /** Ambil nilai setting berdasarkan key. */
    public static function get(string $key, $default = null)
    {
        $all = Cache::remember('settings.all', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });

        return $all[$key] ?? $default;
    }

    /** Simpan / perbarui satu setting. */
    public static function put(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('settings.all');
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings.all'));
        static::deleted(fn () => Cache::forget('settings.all'));
    }
}
