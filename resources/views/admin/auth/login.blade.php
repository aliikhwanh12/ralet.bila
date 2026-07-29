<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin — {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height:100vh;">
    <div class="card shadow-sm" style="width:100%;max-width:380px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" width="48" height="48">
                <h5 class="fw-bold mt-2 mb-0" style="color: var(--sky-dark);">Login Admin</h5>
                <p class="text-muted small">{{ config('app.name') }}</p>
            </div>

            @include('partials.flash')

            <form method="POST" action="{{ route('admin.login.attempt') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                    <label for="remember" class="form-check-label small">Ingat saya</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk
                </button>
            </form>
        </div>
    </div>
</body>
</html>
