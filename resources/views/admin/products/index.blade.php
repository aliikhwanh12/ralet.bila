@extends('layouts.admin')

@section('title', 'Produk')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Produk
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($products->isEmpty())
                <p class="text-muted text-center py-4 mb-0"><i class="bi bi-box"></i> Belum ada produk.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr class="text-muted small">
                                <th>Produk</th><th>Jenis</th><th>Status</th><th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($product->image_url)
                                                <img src="{{ $product->image_url }}" width="42" height="42" style="object-fit:cover;border-radius:.4rem;">
                                            @else
                                                <div class="product-thumb-placeholder" style="width:42px;height:42px;font-size:1.1rem;border-radius:.4rem;">{{ Str::substr($product->name,0,1) }}</div>
                                            @endif
                                            <span class="fw-semibold">{{ $product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="small">
                                        <a href="{{ route('admin.products.variants.index', $product) }}">
                                            {{ $product->variants_count }} jenis
                                        </a>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.products.toggle', $product) }}" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-sm btn-outline-{{ $product->is_active ? 'success' : 'secondary' }}">
                                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline"
                                              onsubmit="return confirm('Hapus produk ini?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $products->links() }}
            @endif
        </div>
    </div>
@endsection
