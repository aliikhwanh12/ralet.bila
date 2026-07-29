<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariantOption;

class CatalogController extends Controller
{
    /** Katalog produk aktif. */
    public function index()
    {
        $products = Product::active()->latest()->get();

        return view('catalog.index', compact('products'));
    }

    /** Detail satu produk: daftar jenis & durasi yang tersedia. */
    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        $product->load(['variants' => function ($query) {
            $query->active()->orderBy('sort_order')
                ->with(['options' => fn ($q) => $q->active()->orderBy('sort_order')]);
        }]);

        return view('catalog.show', compact('product'));
    }

    /** Halaman checkout (form pemesanan) untuk satu kombinasi jenis+durasi. */
    public function checkout(ProductVariantOption $option)
    {
        abort_unless(
            $option->is_active
                && $option->variant?->is_active
                && $option->variant->product?->is_active,
            404
        );

        if (! $option->is_in_stock) {
            return redirect()
                ->route('catalog.show', $option->variant->product)
                ->with('error', 'Maaf, stok pilihan ini sedang habis.');
        }

        $option->load('variant.product');

        return view('catalog.checkout', compact('option'));
    }
}
