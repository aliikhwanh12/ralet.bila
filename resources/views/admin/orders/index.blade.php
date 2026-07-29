@extends('layouts.admin')

@section('title', 'Pesanan')

@section('content')
    <div class="btn-group mb-3">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ !$status ? 'btn-primary' : 'btn-outline-primary' }}">Semua</a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">Menunggu Bayar</a>
        <a href="{{ route('admin.orders.index', ['status' => 'waiting']) }}" class="btn btn-sm {{ $status === 'waiting' ? 'btn-primary' : 'btn-outline-primary' }}">Menunggu Konfirmasi</a>
        <a href="{{ route('admin.orders.index', ['status' => 'paid']) }}" class="btn btn-sm {{ $status === 'paid' ? 'btn-primary' : 'btn-outline-primary' }}">Lunas</a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="btn btn-sm {{ $status === 'cancelled' ? 'btn-primary' : 'btn-outline-primary' }}">Dibatalkan</a>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($orders->isEmpty())
                <p class="text-muted text-center mb-0 py-4"><i class="bi bi-inbox"></i> Tidak ada pesanan.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr class="text-muted small">
                                <th>Kode</th><th>Tanggal</th><th>Produk</th><th>Customer</th><th>Qty</th><th>Total</th><th>Status</th><th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $o)
                                <tr>
                                    <td class="small fw-bold">{{ $o->order_code }}</td>
                                    <td class="small">{{ $o->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="small">
                                        {{ $o->product_name ?? '-' }}
                                        @if ($o->variant_name || $o->duration_label)
                                            <br><span class="text-muted">{{ $o->variant_name }} @if($o->variant_name && $o->duration_label) &middot; @endif {{ $o->duration_label }}</span>
                                        @endif
                                    </td>
                                    <td class="small">
                                        {{ $o->customer_name }}<br>
                                        <span class="text-muted">{{ $o->customer_whatsapp }}</span>
                                    </td>
                                    <td class="small">{{ $o->quantity }}</td>
                                    <td class="small">{{ rupiah($o->total_price) }}</td>
                                    <td><span class="badge text-bg-{{ $o->status_color }} badge-status">{{ $o->status_label }}</span></td>
                                    <td><a href="{{ route('admin.orders.show', $o) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Detail</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $orders->links() }}
            @endif
        </div>
    </div>
@endsection
