<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hot Stone Bath Booking')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f6f0e7;
            color: #2e2a27;
        }
        .hero-bg {
            background:
                radial-gradient(circle at 15% 20%, rgba(240, 196, 108, 0.16), transparent 35%),
                radial-gradient(circle at 85% 35%, rgba(32, 88, 63, 0.2), transparent 35%),
                linear-gradient(120deg, #8d1f1f 0%, #c6362c 55%, #d98b2b 100%);
            color: #fff;
            border: 2px solid rgba(240, 196, 108, 0.35);
        }
        .card-shadow {
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.08);
            border: none;
        }
        .bath-thumb {
            height: 220px;
            object-fit: cover;
        }
        .section-title {
            color: #7b1c1c;
            letter-spacing: 0.02em;
        }
        .bhutan-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border-radius: 999px;
            background: rgba(240, 196, 108, 0.2);
            border: 1px solid rgba(240, 196, 108, 0.45);
            color: #fff7df;
            font-size: 0.85rem;
            padding: 0.3rem 0.75rem;
            margin-bottom: 0.75rem;
        }
        .hero-soft-link {
            color: #fff7df;
            text-decoration: none;
            border-bottom: 1px dashed rgba(255, 247, 223, 0.75);
            padding-bottom: 0.1rem;
        }
        .hero-soft-link:hover {
            color: #ffffff;
            border-bottom-color: #ffffff;
        }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}">
            <img src="/image/system-logo.png" alt="Hot Stone Bath Logo" style="height: 45px; width: auto;">
            <span>Hot Stone Bath Booking System</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#how-to-book">How to Book</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#about-us">About Us</a></li>
            </ul>
            <div class="d-flex gap-2 ms-lg-3 align-items-center">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm px-3">Dashboard</a>
                        <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-light btn-sm px-3">Logout</button>
                        </form>
                    @elseif(Auth::user()->role === 'owner')
                        <a href="{{ route('owner.dashboard') }}" class="btn btn-outline-light btn-sm px-3">Dashboard</a>
                        <form action="{{ route('owner.logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-light btn-sm px-3">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('guest.dashboard') }}" class="btn btn-outline-light btn-sm px-3">Dashboard</a>
                        <form action="{{ route('guest.logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-light btn-sm px-3">Logout</button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3">Login</a>
                    <a href="{{ route('guest.register') }}" class="btn btn-light btn-sm px-3">Signup</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main class="py-4">
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
