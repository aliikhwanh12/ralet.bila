<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-sky sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('catalog.index') }}">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" width="32" height="32">
                {{ config('app.name') }}
            </a>
            <div class="ms-auto">
                <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-grid"></i> Katalog
                </a>
            </div>
        </div>
    </nav>

    <main class="container my-4 flex-grow-1">
        @include('partials.flash')
        @yield('content')
    </main>

    <footer class="site-footer py-3 mt-auto">
        <div class="container text-center small">
            {{ setting('footer_text', '© ' . date('Y') . ' ' . config('app.name')) }}
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
