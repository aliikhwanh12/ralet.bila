<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin — @yield('title', 'Dashboard')</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        {{-- Sidebar --}}
        <aside class="col-lg-2 col-md-3 admin-sidebar p-3">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand d-flex align-items-center gap-2 mb-4 text-decoration-none">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" width="28" height="28">
                <span class="fw-bold" style="color: var(--sky-dark);">{{ config('app.name') }}</span>
            </a>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                        <i class="bi bi-receipt"></i> Pesanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                        <i class="bi bi-box-seam"></i> Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.edit') }}">
                        <i class="bi bi-gear"></i> Pengaturan
                    </a>
                </li>
            </ul>
            <hr>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </aside>

        {{-- Main --}}
        <main class="col-lg-10 col-md-9 py-4 px-4" style="background: var(--sky-lighter); min-height: 100vh;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0" style="color: var(--sky-dark);">@yield('title', 'Dashboard')</h4>
                <a href="{{ route('catalog.index') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-box-arrow-up-right"></i> Lihat Situs
                </a>
            </div>
            @include('partials.flash')
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
