@extends('layouts.admin')

@section('title', 'Jenis — ' . $product->name)

@section('content')
    <a href="{{ route('admin.products.index') }}" class="btn btn-link ps-0 mb-2 text-decoration-none">
        <i class="bi bi-arrow-left"></i> Kembali ke Produk
    </a>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">{{ $product->name }} — Jenis</h5>
        <a href="{{ route('admin.products.variants.create', $product) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Jenis
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($variants->isEmpty())
                <p class="text-muted text-center py-4 mb-0"><i class="bi bi-tags"></i> Belum ada jenis untuk produk ini.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr class="text-muted small">
                                <th>Nama</th><th>Durasi</th><th>Urutan</th><th>Status</th><th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($variants as $variant)
                                <tr>
                                    <td class="fw-semibold">{{ $variant->name }}</td>
                                    <td class="small">
                                        <a href="{{ route('admin.variants.options.index', $variant) }}">
                                            {{ $variant->options_count }} durasi
                                        </a>
                                    </td>
                                    <td class="small">{{ $variant->sort_order }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.variants.toggle', $variant) }}" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-sm btn-outline-{{ $variant->is_active ? 'success' : 'secondary' }}">
                                                {{ $variant->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.variants.edit', $variant) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                        <form method="POST" action="{{ route('admin.variants.destroy', $variant) }}" class="d-inline"
                                              onsubmit="return confirm('Hapus jenis ini beserta seluruh durasinya?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $variants->links() }}
            @endif
        </div>
    </div>
@endsection
