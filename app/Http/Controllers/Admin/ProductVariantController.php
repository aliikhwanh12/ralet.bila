<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->variants()->withCount('options')->orderBy('sort_order')->paginate(15);

        return view('admin.variants.index', compact('product', 'variants'));
    }

    public function create(Product $product)
    {
        return view('admin.variants.create', ['product' => $product, 'variant' => new ProductVariant()]);
    }

    public function store(Request $request, Product $product)
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active');

        $product->variants()->create($data);

        return redirect()->route('admin.products.variants.index', $product)->with('success', 'Jenis berhasil ditambahkan.');
    }

    public function edit(ProductVariant $variant)
    {
        return view('admin.variants.edit', compact('variant'));
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active');

        $variant->update($data);

        return redirect()->route('admin.products.variants.index', $variant->product)->with('success', 'Jenis berhasil diperbarui.');
    }

    public function destroy(ProductVariant $variant)
    {
        $product = $variant->product;
        $variant->delete();

        return redirect()->route('admin.products.variants.index', $product)->with('success', 'Jenis berhasil dihapus.');
    }

    public function toggle(ProductVariant $variant)
    {
        $variant->update(['is_active' => ! $variant->is_active]);

        return back()->with('success', 'Status jenis diperbarui.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
