@extends('layouts.admin')

@section('title', 'Durasi — ' . $variant->name)

@section('content')
    <a href="{{ route('admin.products.variants.index', $variant->product) }}" class="btn btn-link ps-0 mb-2 text-decoration-none">
        <i class="bi bi-arrow-left"></i> Kembali ke Jenis
    </a>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">{{ $variant->product->name }} / {{ $variant->name }} — Durasi</h5>
        <a href="{{ route('admin.variants.options.create', $variant) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Durasi
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($options->isEmpty())
                <p class="text-muted text-center py-4 mb-0"><i class="bi bi-clock-history"></i> Belum ada durasi untuk jenis ini.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr class="text-muted small">
                                <th>Label</th><th>Harga</th><th>Stok</th><th>Urutan</th><th>Status</th><th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($options as $option)
                                <tr>
                                    <td class="fw-semibold">{{ $option->label }}</td>
                                    <td>{{ rupiah($option->price) }}</td>
                                    <td>
                                        @if ($option->stock > 0)
                                            {{ $option->stock }}
                                        @else
                                            <span class="badge text-bg-danger">Habis</span>
                                        @endif
                                    </td>
                                    <td class="small">{{ $option->sort_order }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.options.toggle', $option) }}" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-sm btn-outline-{{ $option->is_active ? 'success' : 'secondary' }}">
                                                {{ $option->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.options.edit', $option) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                        <form method="POST" action="{{ route('admin.options.destroy', $option) }}" class="d-inline"
                                              onsubmit="return confirm('Hapus durasi ini?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $options->links() }}
            @endif
        </div>
    </div>
@endsection
