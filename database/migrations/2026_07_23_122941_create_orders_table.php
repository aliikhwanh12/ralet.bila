<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_option_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name')->nullable();
            $table->string('variant_name')->nullable();
            $table->string('duration_label')->nullable();
            $table->string('customer_name');
            $table->string('customer_whatsapp');
            $table->string('customer_email')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedInteger('unit_price')->default(0);
            $table->unsignedInteger('total_price')->default(0);
            $table->string('status')->default('pending'); // pending | paid | cancelled
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
