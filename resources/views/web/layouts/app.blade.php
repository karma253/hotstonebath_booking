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
            position: relative;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: pointer;
            box-shadow: 
                0 10px 30px rgba(139, 31, 31, 0.25),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .hero-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.5s ease, transform 0.5s ease;
            transform: translate(0, 0);
        }

        .hero-bg:hover {
            transform: translateY(-8px);
            box-shadow: 
                0 20px 50px rgba(139, 31, 31, 0.35),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            background:
                radial-gradient(circle at 15% 20%, rgba(240, 196, 108, 0.22), transparent 35%),
                radial-gradient(circle at 85% 35%, rgba(32, 88, 63, 0.3), transparent 35%),
                linear-gradient(120deg, #9d2f2f 0%, #d64639 55%, #e39a3b 100%);
            border-color: rgba(240, 196, 108, 0.55);
        }

        .hero-bg:hover::before {
            opacity: 1;
        }
        .card-shadow {
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.08);
            border: none;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
        }

        .card-shadow::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }

        .card-shadow:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.18),
                0 0 40px rgba(139, 31, 31, 0.1);
            border-color: transparent;
        }

        .card-shadow:hover::before {
            opacity: 1;
        }

        .card-shadow .card-img-top {
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            filter: brightness(1) saturate(1);
        }

        .card-shadow:hover .card-img-top {
            filter: brightness(1.15) saturate(1.2);
            transform: scale(1.1);
        }

        .card-shadow .card-title,
        .card-shadow .card-body p {
            transition: all 0.3s ease;
        }

        .card-shadow:hover .card-title {
            color: #8d1f1f;
            transform: translateX(4px);
        }

        .card-shadow .btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card-shadow .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.5s ease, height 0.5s ease;
            pointer-events: none;
        }

        .card-shadow .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .card-shadow .btn:active::before {
            width: 300px;
            height: 300px;
        }
        .bath-thumb {
            height: 220px;
            object-fit: cover;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .card:hover .bath-thumb {
            filter: brightness(1.2) saturate(1.3);
            transform: scale(1.15) rotate(2deg);
        }

        .section-title {
            color: #7b1c1c;
            letter-spacing: 0.02em;
            transition: all 0.3s ease;
        }

        .card-shadow:active {
            transform: translateY(-6px) scale(1.01);
        }

        .card:has(> img) {
            cursor: pointer;
        }

        /* Badge and price animations */
        .badge {
            transition: all 0.3s ease;
        }

        .card-shadow:hover .badge {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Price highlight */
        .text-danger {
            transition: all 0.3s ease;
        }

        .card-shadow:hover .text-danger {
            transform: scale(1.1);
            text-shadow: 0 2px 8px rgba(198, 54, 44, 0.3);
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
            transition: all 0.3s ease;
        }

        .hero-bg:hover .bhutan-chip {
            background: rgba(240, 196, 108, 0.35);
            border-color: rgba(240, 196, 108, 0.7);
            transform: scale(1.05);
        }

        .hero-bg h1 {
            transition: all 0.4s ease;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .hero-bg:hover h1 {
            transform: translateX(5px);
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .hero-bg p.lead {
            transition: all 0.4s ease;
        }

        .hero-bg:hover p.lead {
            transform: translateX(5px);
            opacity: 1;
        }

        .hero-bg img {
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            filter: brightness(1) drop-shadow(0 10px 25px rgba(0, 0, 0, 0.15));
        }

        .hero-bg:hover img {
            transform: scale(1.08) rotate(2deg);
            filter: brightness(1.1) drop-shadow(0 15px 35px rgba(0, 0, 0, 0.25));
        }
        .hero-soft-link {
            color: #fff7df;
            text-decoration: none;
            border-bottom: 2px solid rgba(255, 247, 223, 0.75);
            padding-bottom: 0.1rem;
            transition: all 0.3s ease;
            position: relative;
            display: inline-block;
        }

        .hero-soft-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #ffffff;
            transition: width 0.3s ease;
        }

        .hero-soft-link:hover {
            color: #ffffff;
            border-bottom-color: #ffffff;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        .hero-soft-link:hover::after {
            width: 100%;
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
