@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg">
            <div class="card stat-card p-3 h-100">
                <div class="text-muted small"><i class="bi bi-receipt"></i> Total Pesanan</div>
                <div class="stat-value">{{ $totalOrders }}</div>
            </div>
        </div>
        <div class="col-6 col-lg">
            <div class="card stat-card p-3 h-100" style="border-left-color: var(--success);">
                <div class="text-muted small"><i class="bi bi-cash-stack"></i> Pendapatan</div>
                <div class="stat-value">{{ rupiah($revenue) }}</div>
            </div>
        </div>
        <div class="col-6 col-lg">
            <div class="card stat-card p-3 h-100" style="border-left-color: var(--info, #0dcaf0);">
                <div class="text-muted small"><i class="bi bi-patch-question"></i> Perlu Konfirmasi</div>
                <div class="stat-value">{{ $waitingOrders }}</div>
            </div>
        </div>
        <div class="col-6 col-lg">
            <div class="card stat-card p-3 h-100" style="border-left-color: var(--warning);">
                <div class="text-muted small"><i class="bi bi-hourglass-split"></i> Menunggu Bayar</div>
                <div class="stat-value">{{ $pendingOrders }}</div>
            </div>
        </div>
        <div class="col-6 col-lg">
            <div class="card stat-card p-3 h-100">
                <div class="text-muted small"><i class="bi bi-box-seam"></i> Produk Aktif</div>
                <div class="stat-value">{{ $activeProducts }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Pesanan Terbaru</h6>
                    @if ($recentOrders->isEmpty())
                        <p class="text-muted mb-0">Belum ada pesanan.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                    <tr class="text-muted small">
                                        <th>Kode</th><th>Produk</th><th>Customer</th><th>Total</th><th>Status</th><th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentOrders as $o)
                                        <tr>
                                            <td class="small fw-bold">{{ $o->order_code }}</td>
                                            <td class="small">{{ $o->product_name ?? '-' }}</td>
                                            <td class="small">{{ $o->customer_name }}</td>
                                            <td class="small">{{ rupiah($o->total_price) }}</td>
                                            <td><span class="badge text-bg-{{ $o->status_color }} badge-status">{{ $o->status_label }}</span></td>
                                            <td><a href="{{ route('admin.orders.show', $o) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Produk Terlaris</h6>
                    @forelse ($bestSellers as $p)
                        <div class="d-flex justify-content-between align-items-center py-1">
                            <span class="small">{{ $p->name }}</span>
                            <span class="badge text-bg-light border">{{ $p->sold }} terjual</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada data.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
