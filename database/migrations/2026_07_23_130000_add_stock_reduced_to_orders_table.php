<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menandai apakah stok produk sudah dikurangi untuk pesanan ini.
            // Membuat pengurangan/pengembalian stok bersifat idempoten (tidak dobel).
            $table->boolean('stock_reduced')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('stock_reduced');
        });
    }
};
