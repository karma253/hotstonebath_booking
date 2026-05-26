<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Druk Wellness Booking')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f6f0e7;
            color: #2e2a27;
        }
        .hero-bg {
            background:
                radial-gradient(circle at 15% 20%, rgba(94, 163, 255, 0.18), transparent 35%),
                radial-gradient(circle at 85% 35%, rgba(23, 61, 121, 0.24), transparent 35%),
                linear-gradient(120deg, #07101f 0%, #0a2340 52%, #123869 100%);
            color: #fff;
            border: 2px solid rgba(128, 184, 255, 0.35);
            position: relative;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: pointer;
            box-shadow: 
                0 10px 30px rgba(4, 12, 28, 0.45),
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
                0 20px 50px rgba(6, 16, 36, 0.55),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            background:
                radial-gradient(circle at 15% 20%, rgba(126, 189, 255, 0.22), transparent 35%),
                radial-gradient(circle at 85% 35%, rgba(30, 76, 148, 0.3), transparent 35%),
                linear-gradient(120deg, #0a1a33 0%, #11315a 55%, #1a4a84 100%);
            border-color: rgba(150, 198, 255, 0.55);
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

        .site-navbar {
            background: linear-gradient(135deg, #5a3412 0%, #3f210e 100%);
            border-bottom: 1px solid rgba(0, 0, 0, 0.12);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.25);
        }

        /* Keep navbar compact while allowing a larger logo */
        .site-navbar { padding-top: 8px; padding-bottom: 8px; }
        .navbar-brand .brand-logo {
            height: 96px;
            width: auto;
            border-radius: 6px;
            margin-top: -18px; /* let logo overlap without increasing navbar height */
            display: block;
        }
        @media (max-width: 767px) {
            .navbar-brand .brand-logo { height: 56px; margin-top: 0; }
        }

        .site-navbar .nav-link,
        .site-navbar .navbar-brand {
            color: rgba(255, 255, 255, 0.95);
        }

        .site-navbar .nav-link:hover,
        .site-navbar .nav-link:focus,
        .site-navbar .navbar-brand:hover,
        .site-navbar .navbar-brand:focus {
            color: #ffffff;
        }

        .btn,
        .btn-light,
        .btn-outline-light,
        .btn-primary,
        .btn-outline-primary,
        .btn-hero-primary,
        .btn-hero-secondary,
        .view-btn,
        .card-btn,
        .card-btn.solid,
        .card-shadow .btn {
            background-color: #8B4513;
            border-color: #8B4513;
            color: #ffffff;
            font-weight: 700;
        }

        .btn:hover,
        .btn:focus,
        .btn-light:hover,
        .btn-light:focus,
        .btn-outline-light:hover,
        .btn-outline-light:focus,
        .btn-primary:hover,
        .btn-primary:focus,
        .btn-outline-primary:hover,
        .btn-outline-primary:focus,
        .btn-hero-primary:hover,
        .btn-hero-secondary:hover,
        .view-btn:hover,
        .card-btn.solid:hover,
        .card-shadow .btn:hover {
            background-color: #6a3510;
            border-color: #6a3510;
            color: #ffffff;
            box-shadow: 0 8px 18px rgba(0,0,0,0.18);
            transform: translateY(-2px);
        }

        .btn:active,
        .btn-light:active,
        .btn-outline-light:active,
        .btn-primary:active,
        .btn-outline-primary:active {
            background-color: #0a2342;
            border-color: #0a2342;
            color: #ffffff;
        }
        /* Navbar link hover & active behavior */
        .site-navbar .nav-link {
            transition: color 180ms ease, transform 120ms ease, text-shadow 180ms ease;
            position: relative;
        }
        .site-navbar .nav-link:hover,
        .site-navbar .nav-link:focus {
            color: #fff;
            transform: translateY(-3px);
            text-shadow: 0 6px 18px rgba(0,0,0,0.18);
        }
        .site-navbar .nav-link.active,
        .site-navbar .nav-link:active {
            color: #fffbe6;
            border-bottom: 3px solid rgba(255,255,255,0.18);
            padding-bottom: 6px;
            transform: translateY(-4px);
        }
    </style>
    @stack('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark site-navbar">
    <div class="container-fluid px-3">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}" style="position:relative; left:0; top:0;">
            <img src="/image/logo.png" alt="Druk Wellness Logo" class="brand-logo">
            <span>Druk Wellness Booking System</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto gap-lg-2">
                <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('services.index') }}">Services</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('wellness.baths') }}">Wellness Baths</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('how.to.book') }}">How It Works</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('about.us') }}">About Us</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('contact.us') }}">Contact Us</a></li>
            </ul>
            <div class="d-flex gap-2 ms-lg-3 align-items-center">
                {{-- Check if on login/registration pages --}}
                @php
                    $loginRoutes = ['login', 'owner.login', 'guest.login', 'admin.login', 'guest.register'];
                    $isLoginPage = in_array(Route::currentRouteName(), $loginRoutes);
                @endphp
                
                    @if($isLoginPage)
                    <!-- Login/Register pages: Show Login/Signup only -->
                    @if(Route::currentRouteName() !== 'guest.register')
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3">Login</a>
                    @endif
                    <div class="btn-group">
                        <button type="button" class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Signup</button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('guest.register') }}">Sign up as Guest</a></li>
                            <li><a class="dropdown-item" href="{{ route('owner.register') }}">Sign up as Owner</a></li>
                        </ul>
                    </div>
                    @elseif(Route::currentRouteName() === 'home')
                    <!-- Home page: Show Login/Signup only -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">Login</button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('guest.login') }}">Customer Login</a></li>
                                <li><a class="dropdown-item" href="{{ route('owner.login') }}">Owner Login</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.login') }}">Admin Login</a></li>
                                <li><a class="dropdown-item" href="{{ route('staff.login') }}">Staff Login</a></li>
                            </ul>
                        </div>
                        <div class="btn-group ms-2">
                            <button type="button" class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">Signup</button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('guest.register') }}">Sign up as Guest</a></li>
                                <li><a class="dropdown-item" href="{{ route('owner.register') }}">Sign up as Owner</a></li>
                            </ul>
                        </div>
                @else
                    <!-- Other pages: Show Dashboard/Logout for authenticated users -->
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
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">Login</button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('guest.login') }}">Customer Login</a></li>
                                <li><a class="dropdown-item" href="{{ route('owner.login') }}">Owner Login</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.login') }}">Admin Login</a></li>
                                <li><a class="dropdown-item" href="{{ route('staff.login') }}">Staff Login</a></li>
                            </ul>
                        </div>
                        <div class="btn-group ms-2">
                            <button type="button" class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">Signup</button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('guest.register') }}">Sign up as Guest</a></li>
                                <li><a class="dropdown-item" href="{{ route('owner.register') }}">Sign up as Owner</a></li>
                            </ul>
                        </div>
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>

<!-- Footer (minimal) -->
<footer class="mt-5" style="background:#f7efe8;padding:1rem 0;border-top:1px solid rgba(0,0,0,0.04);">
    <div class="container text-center" style="padding:0.5rem 0;">
        <!-- Footer content removed per user request -->
    </div>
</footer>

<main class="py-4">
    <div class="container">
        @if (session('session_expired'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-clock me-2"></i>Session Expired!</strong>
                {{ session('session_expired') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
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
<script>
    // Toggle "active" class on nav link clicks and try to mark current path on load
    document.addEventListener('DOMContentLoaded', function () {
        var links = document.querySelectorAll('.site-navbar .nav-link');
        links.forEach(function (link) {
            link.addEventListener('click', function () {
                links.forEach(function (l) { l.classList.remove('active'); });
                this.classList.add('active');
            });
        });

        // Mark link matching current pathname as active (basic matching)
        var path = location.pathname.replace(/\/$/, '');
        links.forEach(function (link) {
            try {
                var href = link.getAttribute('href') || '';
                var linkPath = href.replace(location.origin, '').replace(/\/$/, '');
                if (linkPath === path || href === path) {
                    link.classList.add('active');
                }
            } catch (e) { /* ignore malformed hrefs */ }
        });
    });
</script>
</body>
</html>
