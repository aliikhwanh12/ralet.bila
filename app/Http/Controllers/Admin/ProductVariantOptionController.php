<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\ProductVariantOption;
use Illuminate\Http\Request;

class ProductVariantOptionController extends Controller
{
    public function index(ProductVariant $variant)
    {
        $options = $variant->options()->orderBy('sort_order')->paginate(15);

        return view('admin.options.index', compact('variant', 'options'));
    }

    public function create(ProductVariant $variant)
    {
        return view('admin.options.create', ['variant' => $variant, 'option' => new ProductVariantOption()]);
    }

    public function store(Request $request, ProductVariant $variant)
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active');

        $variant->options()->create($data);

        return redirect()->route('admin.variants.options.index', $variant)->with('success', 'Durasi berhasil ditambahkan.');
    }

    public function edit(ProductVariantOption $option)
    {
        return view('admin.options.edit', compact('option'));
    }

    public function update(Request $request, ProductVariantOption $option)
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active');

        $option->update($data);

        return redirect()->route('admin.variants.options.index', $option->variant)->with('success', 'Durasi berhasil diperbarui.');
    }

    public function destroy(ProductVariantOption $option)
    {
        $variant = $option->variant;
        $option->delete();

        return redirect()->route('admin.variants.options.index', $variant)->with('success', 'Durasi berhasil dihapus.');
    }

    public function toggle(ProductVariantOption $option)
    {
        $option->update(['is_active' => ! $option->is_active]);

        return back()->with('success', 'Status durasi diperbarui.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
