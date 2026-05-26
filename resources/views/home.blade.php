<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Druk Wellness Booking System</title>
        <link rel="preload" as="image" href="/image/Six Senses Thimphu.jpg">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Playfair+Display:wght@600;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
                <div class="gallery-card">
                    <picture>
                        <source type="image/webp" srcset="/image/optimized/hot%20stone%20bath-1200.webp 1200w, /image/optimized/hot%20stone%20bath-800.webp 800w" sizes="(max-width:900px) 100vw, 50vw">
                        <img src="/image/hot stone bath.png" alt="Hot Stone Bath" class="gallery-card-img" loading="lazy" decoding="async" width="560" height="320">
                    </picture>
                    <div class="gallery-card-content">
                        <div>
                            <div class="gallery-card-title">Hot Stone Bath</div>
                            <div class="gallery-card-location">Thimphu, Dechencholing</div>
                            <div class="gallery-card-description">Relax your body and mind with natural hot stones.</div>
                        </div>
                        <div>
                            <div class="gallery-card-details">
                                <div class="gallery-card-price">Nu. 1,100</div>
                                <div class="gallery-card-capacity">1 Guest</div>
                            </div>
                            <div style="margin-top:10px">
                                <a href="{{ route('services.show', ['service' => 1]) }}" class="gallery-card-btn">View Details ➜</a>
                            </div>
                        </div>
                    </div>
                </div>
            }

            .gallery-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
                margin-bottom: 2rem;
            }

            .gallery-card {
                display:flex;
                background:#fff;
                border-radius:12px;
                overflow:hidden;
                box-shadow:0 10px 24px rgba(0,0,0,0.12);
                transition:transform 0.3s,box-shadow 0.3s;
            }

            .gallery-card-img{width:48%;height:220px;object-fit:cover;display:block}

            .gallery-card-content{padding:18px 22px;flex:1;display:flex;flex-direction:column;justify-content:space-between}

            .gallery-card-title{font-size:1.28rem;color:#3b2412;font-weight:700;margin-bottom:6px}
            .gallery-card-location{color:#7a5a49;font-size:0.95rem;margin-bottom:8px}
            .gallery-card-description{color:#6b4a3a;font-size:0.95rem}

            .gallery-card-details{display:flex;justify-content:space-between;align-items:center;padding-top:12px;border-top:1px solid #f1e9e6;margin-top:12px}
            .gallery-card-price{font-size:1.15rem;color:#3b2412;font-weight:800;background:#fff6f0;padding:6px 10px;border-radius:10px;border:1px solid rgba(0,0,0,0.04)}
            .gallery-card-btn{background:#8f4724;color:#fff;padding:10px 14px;border-radius:8px;text-decoration:none;display:inline-block}

            .gallery-card:hover{transform:translateY(-6px);box-shadow:0 18px 40px rgba(0,0,0,0.14)}

            @media (max-width: 900px){
                .gallery-grid{grid-template-columns:1fr}
                .gallery-card{flex-direction:column}
                .gallery-card-img{width:100%;height:220px}
            }
        }
        .nav-btn.primary{background:#b65b2b;border:none}

        .btn-secondary:hover {
            background: #f8f8f8;
            color: #222;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.06);
        }

        .btn-secondary:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        }

        .hero {
            /* background handled by <picture> for responsive/webp support */
            background: transparent;
            color: #fff;
            padding: 5.5rem 0 2.8rem 0;
            text-align: left;
            margin-bottom: 2.5rem;
            position: relative;
            min-height: 420px;
            display:flex;align-items:center;overflow:hidden
        }

        .hero .hero-bg{position:absolute;left:0;top:0;right:0;bottom:0;z-index:0}
        .hero .hero-bg img{width:100%;height:100%;object-fit:cover;display:block}

        .hero::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, rgba(43,34,26,0.75) 0%, rgba(43,34,26,0.25) 55%, rgba(255,255,255,0) 100%);
            pointer-events: none;
        }

        .hero .hero-inner {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 3.2rem;
            margin-bottom: 0.75rem;
            color: #fff;
            line-height:1.02;
            letter-spacing: -0.5px;
            font-weight:700;
            text-shadow:0 6px 18px rgba(0,0,0,0.35)
        }

        .hero p {
            font-size: 1.05rem;
            margin-bottom: 1.25rem;
            color: #5a5a5a;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        .btn-hero{
            padding:12px 22px;border-radius:8px;background:linear-gradient(180deg,#b65b2b,#8f4724);color:#fff;text-decoration:none;font-weight:700;border:0;box-shadow:0 8px 30px rgba(142,66,34,0.18)
        }
        .btn-ghost{padding:10px 18px;border-radius:8px;background:transparent;color:#fff;border:1px solid rgba(255,255,255,0.12)}

        .btn-large {
            padding: 1rem 2rem;
            font-size: 1.1rem;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .feature-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 24px rgba(0,0,0,0.2);
        }

        .feature-card:active {
            transform: translateY(-2px) scale(0.98);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            color: #e74c3c;
            margin-bottom: 1rem;
        }

        .stats {
            background: #f8f9fa;
            padding: 3rem 0;
            margin: 3rem 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }

        .stat {
            padding: 1.5rem;
        }

        .stat-number {
            font-size: 2.5rem;
            color: #e74c3c;
            font-weight: bold;
        }

        .stat-label {
            color: #666;
            margin-top: 0.5rem;
        }

        .documentation {
            background: white;
            padding: 3rem 0;
        }

        .doc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .doc-item {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #e74c3c;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .doc-item:hover {
            background: white;
            transform: translateX(8px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            border-left: 6px solid #e74c3c;
        }

        .doc-item:active {
            transform: translateX(4px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .doc-item h4 {
            color: #e74c3c;
            margin-bottom: 0.5rem;
        }

        .doc-item p {
            color: #666;
            font-size: 0.9rem;
        }

        .doc-item a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
            margin-top: 1rem;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .doc-item a:hover {
            text-decoration: underline;
            transform: translateX(4px);
        }

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 2rem 0;
            margin-top: 3rem;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            nav a {
                margin-left: 1rem;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .cta-buttons {
                flex-direction: column;
            }

            .btn-large {
                width: 100%;
            }
        }

        .api-status {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .gallery {
            padding: 3rem 0;
            margin: 3rem 0;
        }

        .gallery h2 {
            text-align: center;
            font-size: 2rem;
            color: #3b2412;
            margin-bottom: 2rem;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .gallery-card {
            display:flex;
            background:#fff;
            border-radius:12px;
            overflow:hidden;
            box-shadow:0 10px 24px rgba(0,0,0,0.12);
            transition:transform 0.3s,box-shadow 0.3s;
        }

        .gallery-card-img{width:48%;height:220px;object-fit:cover;display:block}

        .gallery-card-content{padding:18px;flex:1;display:flex;flex-direction:column;justify-content:space-between}

        .gallery-card-title{font-size:1.3rem;color:#3b2412;font-weight:700;margin-bottom:6px}
        .gallery-card-location{color:#7a5a49;font-size:0.95rem;margin-bottom:8px}
        .gallery-card-description{color:#6b4a3a;font-size:0.95rem}

        .gallery-card-details{display:flex;justify-content:space-between;align-items:center;padding-top:12px;border-top:1px solid #f1e9e6;margin-top:12px}
        .gallery-card-price{font-size:1.2rem;color:#b65b2b;font-weight:700}
        .gallery-card-btn{background:#b65b2b;color:#fff;padding:10px 14px;border-radius:8px;text-decoration:none;display:inline-block}

        .gallery-card:hover{transform:translateY(-6px);box-shadow:0 18px 40px rgba(0,0,0,0.14)}

        @media (max-width: 900px){
            .gallery-grid{grid-template-columns:1fr}
            .gallery-card{flex-direction:column}
            .gallery-card-img{width:100%;height:220px}
        }

        .about-us {
            background: #f8f9fa;
            padding: 3rem 0;
            margin: 3rem 0;
        }

        .about-us h2 {
            color: #e74c3c;
            font-size: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .about-us p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
            text-align: justify;
        }

        .services-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 4rem 0;
            margin: 2rem 0;
        }

        .services-section h2 {
            text-align: center;
            font-size: 2.5rem;
            color: #e74c3c;
            margin-bottom: 0.5rem;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 3rem;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 0 auto;
            max-width: 1200px;
        }

        .service-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
            border-color: #e74c3c;
        }

        .service-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }

        .service-card h3 {
            color: #e74c3c;
            margin-bottom: 1rem;
            font-size: 1.4rem;
        }

        .service-card p {
            color: #666;
            font-size: 1rem;
            line-height: 1.5;
        }
        /* Search card overlapping hero */
        .search-card{background:#fff;border-radius:14px;padding:16px;box-shadow:0 30px 60px rgba(27,18,12,0.12);max-width:1100px;margin:-70px auto 28px;display:flex;gap:12px;align-items:center;border:1px solid rgba(0,0,0,0.04)}
        .search-card .field{flex:1}
        .search-card label{display:block;font-size:12px;color:#777;margin-bottom:6px}
        .search-card select,.search-card input{width:100%;padding:12px;border-radius:10px;border:1px solid #eee}

        /* Feature strip */
        .benefit-strip{background:linear-gradient(180deg,#fff8f3,#fbf6f2);border-radius:14px;padding:16px 20px;display:flex;gap:18px;justify-content:space-between;align-items:center;margin:18px auto;max-width:1100px;border:1px solid rgba(0,0,0,0.03)}

        /* Popular cards tweaks */
        .gallery-card{display:flex;gap:18px;padding:18px}
        .gallery-card-img{width:42%;height:140px;object-fit:cover}

        /* Footer darker */
        footer{background:#2b160f;color:#f3e9e3}
        footer a{color:#f8e9df}
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="brand">
                    <div class="mark">🪷</div>
                    <div>
                        <div style="font-size:14px;opacity:0.9">Druk Wellness</div>
                        <div style="font-size:11px;opacity:0.8;margin-top:2px">Booking System</div>
                    </div>
                </div>

                <nav>
                    <a href="#">Home</a>
                    <a href="#features">Services</a>
                    <a href="{{ route('services.index') }}">Wellness Baths</a>
                    <a href="{{ route('about') }}">How It Works</a>
                    <a href="{{ route('about') }}">About Us</a>
                    <a href="{{ route('contact') }}">Contact Us</a>
                </nav>

                <div class="nav-actions">
                    <a href="/login" class="nav-btn">Login</a>
                    <a href="/register" class="nav-btn primary">Sign Up</a>
                </div>
            </div>
        </div>
    </header>

    <div class="hero">
        <picture class="hero-bg">
            <source type="image/webp" srcset="/image/optimized/Six%20Senses%20Thimphu-1600.webp 1600w, /image/optimized/Six%20Senses%20Thimphu-1200.webp 1200w, /image/optimized/Six%20Senses%20Thimphu-800.webp 800w" sizes="100vw">
            <img src="/image/Six Senses Thimphu.jpg" alt="Druk Wellness hero" loading="eager" decoding="sync">
        </picture>
        <div class="container">
            <div class="hero-inner">
                <h1>Relax. Rejuvenate.<br>Restore Your Balance.</h1>
                <p style="max-width:640px;margin-top:12px;color:rgba(255,255,255,0.9)">Discover authentic hot stone baths and wellness services across Bhutan. Easy booking, clear pricing, and trusted wellness experiences.</p>
                <div style="margin-top:18px" class="cta-buttons">
                    <a href="{{ route('services.index') }}" class="btn-hero">Explore Services ➜</a>
                    <a href="{{ route('about') }}" class="btn-ghost">How It Works</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Rounded search card overlapping hero -->
    <div class="container">
        <div class="search-card">
            <div class="field">
                <label>Area</label>
                <select name="area">
                    <option value="">Thimphu Town Center</option>
                    @include('partials.areas.thimphu', ['default' => 'Thimphu Town Center (core city area)'])
                </select>
            </div>
            <div class="field">
                <label>Service Type</label>
                <select>
                    <option>All Services</option>
                    <option>Hot Stone Bath</option>
                    <option>Herbal Steam Bath</option>
                </select>
            </div>
            <div class="field">
                <label>Date</label>
                <input type="date">
            </div>
            <div style="width:120px">
                <label>Guests</label>
                <select>
                    <option>1 Guest</option>
                    <option>2 Guests</option>
                </select>
            </div>
            <div style="width:160px">
                <label style="visibility:hidden">Search</label>
                <button class="btn-hero" style="width:100%">Search Now</button>
            </div>
        </div>
    </div>

    <!-- Benefit strip (trusted, authentic, easy booking, support) -->
    <div class="container">
        <div class="benefit-strip">
            <div style="flex:1;display:flex;gap:12px;align-items:center">
                <div style="width:44px;height:44px;border-radius:8px;background:#fff6f0;display:flex;align-items:center;justify-content:center">🛡️</div>
                <div>
                    <div style="font-weight:700;color:#2b160f">Trusted & Verified</div>
                    <div style="font-size:13px;color:#6b4a3a">All services are verified for your safety and peace of mind.</div>
                </div>
            </div>
            <div style="flex:1;display:flex;gap:12px;align-items:center">
                <div style="width:44px;height:44px;border-radius:8px;background:#fff6f0;display:flex;align-items:center;justify-content:center">🌿</div>
                <div>
                    <div style="font-weight:700;color:#2b160f">Authentic Wellness</div>
                    <div style="font-size:13px;color:#6b4a3a">Experience authentic Bhutanese wellness therapies.</div>
                </div>
            </div>
            <div style="flex:1;display:flex;gap:12px;align-items:center">
                <div style="width:44px;height:44px;border-radius:8px;background:#fff6f0;display:flex;align-items:center;justify-content:center">📅</div>
                <div>
                    <div style="font-weight:700;color:#2b160f">Easy Booking</div>
                    <div style="font-size:13px;color:#6b4a3a">Book in minutes with clear pricing and availability.</div>
                </div>
            </div>
            <div style="flex:1;display:flex;gap:12px;align-items:center">
                <div style="width:44px;height:44px;border-radius:8px;background:#fff6f0;display:flex;align-items:center;justify-content:center">🎧</div>
                <div>
                    <div style="font-weight:700;color:#2b160f">24/7 Support</div>
                    <div style="font-size:13px;color:#6b4a3a">We are here to help you anytime you need assistance.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search / Filter Bar -->
    <div class="container" style="margin-top: -40px;">
        <div style="background: white; padding: 18px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.06); display:flex; gap:12px; align-items:center;">
            <div style="flex:1">
                <label style="display:block;font-size:12px;color:#666;margin-bottom:6px">Area (Thimphu)</label>
                <select name="area" style="width:100%;padding:10px;border:1px solid #e6e6e6;border-radius:8px">
                    <option value="">Select Area</option>
                    @include('partials.areas.thimphu', ['default' => 'Thimphu Town Center (core city area)'])
                </select>
            </div>
            <div style="flex:1">
                <label style="display:block;font-size:12px;color:#666;margin-bottom:6px">Service Type</label>
                <select style="width:100%;padding:10px;border:1px solid #e6e6e6;border-radius:8px">
                    <option value="">All Services</option>
                    <option>Hot Stone Bath</option>
                    <option>Herbal Steam Bath</option>
                    <option>Aromatherapy</option>
                </select>
            </div>
            <div style="flex:1">
                <label style="display:block;font-size:12px;color:#666;margin-bottom:6px">Date</label>
                <input type="date" style="width:100%;padding:10px;border:1px solid #e6e6e6;border-radius:8px">
            </div>
            <div style="width:140px">
                <label style="display:block;font-size:12px;color:#666;margin-bottom:6px">Guests</label>
                <select style="width:100%;padding:10px;border:1px solid #e6e6e6;border-radius:8px">
                    <option>1 Guest</option>
                    <option>2 Guests</option>
                    <option>3 Guests</option>
                </select>
            </div>
            <div style="width:160px">
                <label style="display:block;font-size:12px;color:transparent;margin-bottom:6px">Search</label>
                <button class="btn btn-primary" style="width:100%;">Search Now</button>
            </div>
        </div>
    </div>

    <!-- Browse Services By Category Section -->
    <section class="services-section">
        <div class="container">
            <h2>Browse Services By Category</h2>
            <p class="section-subtitle">Discover authentic Bhutanese wellness experiences</p>
            <div class="services-grid">
                <a href="{{ route('services.by.category', 'hot-stone-bath') }}" class="service-card">
                    <div class="service-icon">🛁</div>
                    <h3>Hot Stone Bath</h3>
                    <p>Traditional hot stone therapy for relaxation and healing</p>
                </a>

                <a href="{{ route('services.by.category', 'medicinal-water') }}" class="service-card">
                    <div class="service-icon">💧</div>
                    <h3>Medicinal Water</h3>
                    <p>Therapeutic mineral-rich water baths for wellness</p>
                </a>

                <!-- Limited categories: Hot Stone Bath and Medicinal Water -->
            </div>
        </div>
    </section>

    <section class="about-us">
        <div class="container">
            <h2>About Us</h2>
            <p>This system will make it easy for guests to discover and book authentic Bhutanese wellness experiences. You can browse trusted bath facilities, see available dates, read reviews, and book a menchu service in just a few clicks. Once you find a bath you like, check the calendar, select your preferred time, and pay securely. The bath owner will confirm your booking and send you the details. After your visit, you can share your review to help other guests. This system connects you with verified bath owners who offer genuine traditional wellness treatments.</p>
        </div>
    </section>

    <div class="container">
        <section id="features" class="features">
            <div class="feature-card">
                <div class="feature-icon">🏘️</div>
                <h3>Provider Management</h3>
                <p>Owners can register baths, manage services, upload images, and control availability</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👥</div>
                <h3>Guest Booking</h3>
                <p>Guests search for baths, check real-time availability, and book with multiple payment options</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔐</div>
                <h3>Admin Verification</h3>
                <p>Admins verify providers, review documents, and manage platform integrity</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⭐</div>
                <h3>Reviews & Ratings</h3>
                <p>Guests can rate and review completed bookings to build trust in the platform</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Analytics</h3>
                <p>Providers get detailed reports on bookings, revenue, and customer insights</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌍</div>
                <h3>Multi-Region</h3>
                <p>Coverage across Thimphu with detailed area mapping and localized content</p>
            </div>
        </section>

    </div>

    <section class="gallery">
        <div class="container">
            <h2 style="text-align:center;margin-bottom:1rem">Popular Wellness Services</h2>
            <div class="gallery-grid">
                <div class="gallery-card">
                    <img src="/image/hot stone bath.png" alt="Hot Stone Bath" class="gallery-card-img" loading="lazy" decoding="async" width="560" height="320">
                    <div class="gallery-card-content">
                        <div class="gallery-card-title">Hot Stone Bath</div>
                        <div class="gallery-card-location">Motithang</div>
                        <div class="gallery-card-description">Relax your body and mind with natural hot stones.</div>
                        <div class="gallery-card-details">
                            <div class="gallery-card-price">Nu. 800</div>
                            <div class="gallery-card-capacity">1 Guest</div>
                        </div>
                        <a href="{{ route('services.show', ['service' => 1]) }}" class="gallery-card-btn">View Details</a>
                    </div>
                </div>

                <div class="gallery-card">
                    <picture>
                        <source type="image/webp" srcset="/image/optimized/Herbal%20Steam%20Bath-1200.webp 1200w, /image/optimized/Herbal%20Steam%20Bath-800.webp 800w" sizes="(max-width:900px) 100vw, 50vw">
                        <img src="/image/Herbal Steam Bath.png" alt="Herbal Steam Bath" class="gallery-card-img" loading="lazy" decoding="async" width="560" height="320">
                    </picture>
                    <div class="gallery-card-content">
                        <div>
                            <div class="gallery-card-title">Herbal Steam Bath</div>
                            <div class="gallery-card-location">Changzamthang</div>
                            <div class="gallery-card-description">Detoxify and rejuvenate with herbal steam therapy.</div>
                        </div>
                        <div>
                            <div class="gallery-card-details">
                                <div class="gallery-card-price">Nu. 900</div>
                                <div class="gallery-card-capacity">1 Guest</div>
                            </div>
                            <div style="margin-top:10px">
                                <a href="{{ route('services.show', ['service' => 2]) }}" class="gallery-card-btn">View Details ➜</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="gallery-card">
                    <picture>
                        <source type="image/webp" srcset="/image/optimized/medicinal%20water-1200.webp 1200w, /image/optimized/medicinal%20water-800.webp 800w" sizes="(max-width:900px) 100vw, 50vw">
                        <img src="/image/medicinal water.png" alt="Traditional Bhutanese Bath" class="gallery-card-img" loading="lazy" decoding="async" width="560" height="320">
                    </picture>
                    <div class="gallery-card-content">
                        <div>
                            <div class="gallery-card-title">Medicinal Water</div>
                            <div class="gallery-card-location">Thimphu, Phajoding</div>
                            <div class="gallery-card-description">Heal and rejuvenate with natural medicinal water.</div>
                        </div>
                        <div>
                            <div class="gallery-card-details">
                                <div class="gallery-card-price">Nu. 900</div>
                                <div class="gallery-card-capacity">1 Guest</div>
                            </div>
                            <div style="margin-top:10px">
                                <a href="{{ route('services.show', ['service' => 3]) }}" class="gallery-card-btn">View Details ➜</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="gallery-card">
                    <img src="/image/Spa & Massage Therapy.png" alt="Aromatherapy Bath" class="gallery-card-img">
                    <div class="gallery-card-content">
                        <div class="gallery-card-title">Aromatherapy Bath</div>
                        <div class="gallery-card-location">Olakha</div>
                        <div class="gallery-card-description">Unwind with natural oils and calming aromas.</div>
                        <div class="gallery-card-details">
                            <div class="gallery-card-price">Nu. 900</div>
                            <div class="gallery-card-capacity">1 Guest</div>
                        </div>
                        <a href="{{ route('services.show', ['service' => 4]) }}" class="gallery-card-btn">View Details</a>
                    </div>
                </div>
            </div>
            <div style="text-align:center;margin-top:1.5rem">
                <a href="{{ route('services.index') }}" class="btn btn-secondary" style="padding:10px 20px;border-radius:8px;">View All Services</a>
            </div>
        </div>
    </section>

    
    <!-- Dark rounded feature bar matching screenshot -->
    <div style="max-width:1100px;margin:30px auto;padding:18px;border-radius:12px;background:linear-gradient(90deg,#3b2412,#2e160e);color:#fff;box-shadow:0 12px 36px rgba(0,0,0,0.12)">
        <div style="display:flex;gap:18px;align-items:center;justify-content:space-between;flex-wrap:wrap;padding:6px 10px">
            <div style="flex:1;min-width:180px;display:flex;gap:10px;align-items:center">
                <div style="width:56px;height:56px;border-radius:10px;background:rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:center">🌿</div>
                <div>
                    <div style="font-weight:700">Natural & Healing</div>
                    <div style="font-size:13px;opacity:0.9">Pure natural therapies for complete relaxation.</div>
                </div>
            </div>

            <div style="flex:1;min-width:180px;display:flex;gap:10px;align-items:center">
                <div style="width:56px;height:56px;border-radius:10px;background:rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:center">💧</div>
                <div>
                    <div style="font-weight:700">Clean & Hygienic</div>
                    <div style="font-size:13px;opacity:0.9">Well-maintained and hygiene facilities.</div>
                </div>
            </div>

            <div style="flex:1;min-width:180px;display:flex;gap:10px;align-items:center">
                <div style="width:56px;height:56px;border-radius:10px;background:rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:center">🌳</div>
                <div>
                    <div style="font-weight:700">Peaceful Environment</div>
                    <div style="font-size:13px;opacity:0.9">Serene and peaceful locations for true relaxation.</div>
                </div>
            </div>

            <div style="flex:1;min-width:220px;display:flex;gap:10px;align-items:center;justify-content:flex-end">
                <div style="text-align:right">
                    <div style="font-weight:700">Need Help Booking?</div>
                    <div style="font-size:13px;opacity:0.9">Our support team is always ready to assist you.</div>
                    <div style="margin-top:8px"><a href="{{ route('contact') }}" style="background:#fff;color:#3b2412;padding:8px 12px;border-radius:8px;text-decoration:none">Contact Us ➜</a></div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div style="display:flex;gap:2rem;justify-content:space-between;flex-wrap:wrap;padding:2rem 0;">
                <div style="min-width:220px;">
                    <div class="logo">🛁 Druk Wellness</div>
                    <p style="color:#f0e6e1;margin-top:8px;max-width:320px">Your trusted platform for discovering and booking traditional Bhutanese wellness baths and services in Thimphu.</p>
                </div>

                <div style="min-width:220px;">
                    <strong>Quick Links</strong>
                    <ul style="list-style:none;padding:0;margin-top:8px;color:#f0e6e1">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('services.index') }}">Services</a></li>
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="/owner/login">Owner Login</a></li>
                        <li><a href="/owner/register">Owner Register</a></li>
                        <li><a href="/bookings">My Bookings</a></li>
                    </ul>
                </div>

                <div style="min-width:240px;">
                    <strong>Contact</strong>
                    <p style="color:#f0e6e1;margin-top:8px">info@drukwellness.bt<br>Thimphu, Bhutan<br>+975 17 123 456</p>
                    <p style="color:#f0e6e1;margin-top:8px">Mon - Sun: 8:00 AM - 8:00 PM</p>
                </div>
            </div>

            <div style="text-align:center;border-top:1px solid rgba(255,255,255,0.06);padding-top:12px;color:#f0e6e1">&copy; 2024 Druk Wellness Booking System. All rights reserved.</div>
        </div>
    </footer>
</body>
</html>
