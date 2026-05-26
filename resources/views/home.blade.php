<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wellness Booking</title>
        <link rel="preload" as="image" href="/image/Six Senses Thimphu.jpg">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Playfair+Display:wght@600;700;900&display=swap" rel="stylesheet">
    <style>
                :root{
                    --brown-900:#3b1f0f;
                    --brown-800:#2e160e;
                    --brown-700:#8f4724;
                    --brown-600:#b65b2b;
                    --cream:#f5efe7;
                    --panel:#ffffff;
                    --muted:#7a5a49;
                    --gold:#d9a05a;
                    --nav-height:72px;
                }

                *{margin:0;padding:0;box-sizing:border-box}
                body{font-family:'Inter',system-ui,-apple-system,'Segoe UI',Roboto,Arial;line-height:1.6;color:var(--brown-900);background:var(--cream)}

                /* Header */
                header{background:linear-gradient(180deg,var(--brown-900),var(--brown-800));color:#fff;padding:12px 0;position:sticky;top:0;z-index:200;backdrop-filter:blur(4px);border-bottom:1px solid rgba(255,255,255,0.03);height:var(--nav-height)}
                .container{max-width:1200px;margin:0 auto;padding:0 20px}
                .header-content{display:flex;align-items:center;justify-content:space-between;gap:12px;position:relative}
                .brand{display:flex;align-items:center;gap:10px;color:#fff;font-weight:700}
                .brand img{height:36px}
                .brand div{color:#fff}
                .brand .mark{width:44px;height:44px;border-radius:50%;background:rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:center;font-size:18px;color:#fff}
                nav{display:flex;gap:20px;align-items:center}
                .main-nav{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);display:flex;gap:22px;align-items:center}
                .main-nav a{color:rgba(255,255,255,0.92);padding:8px 12px;font-weight:600;font-size:15px;position:relative}
                .main-nav a.active::after{content:'';position:absolute;left:50%;transform:translateX(-50%);bottom:-14px;height:3px;width:48%;background:var(--gold);border-radius:3px}
                nav a{color:rgba(255,255,255,0.95);text-decoration:none;padding:8px 6px;position:relative}
                nav a:hover{opacity:0.95}
                nav a.active::after{content:'';position:absolute;left:0;right:0;bottom:-10px;height:3px;background:var(--gold);border-radius:2px;width:60%;margin:0 auto}
                .nav-actions a{margin-left:10px}
                .nav-btn{padding:8px 14px;border-radius:8px;border:1px solid rgba(255,255,255,0.12);background:transparent;color:#fff;text-decoration:none}
                .nav-btn.primary{background:linear-gradient(180deg,var(--brown-600),var(--brown-700));border:none}

                /* Hero */
                .hero{position:relative;min-height:680px;display:flex;align-items:center;padding:8rem 0 3.5rem;overflow:hidden}
                .hero .hero-bg{position:absolute;inset:0;z-index:0;filter:contrast(0.92) saturate(0.9)}
                .hero .hero-bg img{width:100%;height:100%;object-fit:cover;object-position:right center;display:block}
                .hero .hero-overlay{position:absolute;inset:0;background:linear-gradient(115deg,rgba(43,21,15,0.84) 0%,rgba(43,21,15,0.42) 48%,rgba(0,0,0,0.04) 100%);z-index:1}
                .hero .hero-inner{position:relative;z-index:2;max-width:760px;padding-left:64px;padding-top:36px}
                .hero .subtitle{font-size:13px;letter-spacing:2.4px;color:var(--gold);font-weight:700;margin-bottom:14px;text-transform:uppercase}
                .hero h1{font-family:'Playfair Display',Georgia,serif;font-size:5.2rem;color:#fff;line-height:1.02;margin:0 0 14px;text-shadow:0 12px 42px rgba(0,0,0,0.52);font-weight:700}
                .hero p{max-width:640px;color:rgba(255,255,255,0.95);margin-bottom:18px;font-size:1.05rem}
                .cta-buttons{display:flex;gap:14px}
                .btn-hero{padding:12px 24px;border-radius:8px;background:linear-gradient(180deg,var(--brown-600),var(--brown-700));color:#fff;text-decoration:none;font-weight:800;border:0;box-shadow:0 10px 36px rgba(142,66,34,0.22)}
                .btn-ghost{padding:10px 18px;border-radius:8px;background:transparent;color:#fff;border:1px solid rgba(255,255,255,0.12)}

                /* Search card */
                .search-card{background:var(--panel);border-radius:18px;padding:18px 22px;box-shadow:0 52px 120px rgba(27,18,12,0.24);max-width:1150px;margin:-90px auto 28px;width:calc(100% - 80px);display:flex;gap:16px;align-items:center;border:1px solid rgba(0,0,0,0.06);position:relative;z-index:120;transform:translateY(12px)}
                .search-card .field{flex:1}
                .search-card label{display:block;font-size:12px;color:var(--muted);margin-bottom:6px}
                .search-card select,.search-card input{width:100%;padding:12px 14px;border-radius:10px;border:1px solid #f0eae4;background:#fff}
                .search-card .search-btn{background:linear-gradient(180deg,var(--brown-600),var(--brown-700));color:#fff;padding:12px 18px;border-radius:10px;border:none;font-weight:700}

                /* Feature strip */
                .benefit-strip{background:linear-gradient(180deg,#fff8f3,#fbf6f2);border-radius:18px;padding:18px;display:flex;gap:18px;justify-content:space-between;align-items:center;margin:18px auto;max-width:1100px;border:1px solid rgba(0,0,0,0.03)}
                .benefit-item{display:flex;gap:12px;align-items:center}
                .benefit-item .icon{width:48px;height:48px;border-radius:10px;background:#fff6f0;display:flex;align-items:center;justify-content:center}

                /* Popular cards */
                .gallery{padding:3rem 0;margin:3rem 0}
                .gallery h2{text-align:center;font-size:1.9rem;color:var(--brown-900);margin-bottom:12px}
                .gallery h2{position:relative}
                .gallery h2::after{content:'';display:block;width:48px;height:6px;background:var(--gold);border-radius:6px;margin:12px auto 0}
                .gallery-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;margin-bottom:2rem}
                .gallery-card{display:flex;background:var(--panel);border-radius:14px;overflow:hidden;box-shadow:0 22px 56px rgba(0,0,0,0.18);transition:transform 0.28s}
                .gallery-card-img{width:48%;height:260px;object-fit:cover;display:block}
                .gallery-card-content{padding:22px 28px;flex:1;display:flex;flex-direction:column;justify-content:space-between;background:transparent}
                .gallery-card-title{font-size:1.28rem;color:var(--brown-900);font-weight:800;margin-bottom:6px}
                .gallery-card-location{color:var(--muted);font-size:0.95rem;margin-bottom:8px}
                .gallery-card-description{color:#6b4a3a;font-size:0.95rem}
                .gallery-card-details{display:flex;justify-content:space-between;align-items:center;padding-top:12px;border-top:1px solid #f1e9e6;margin-top:12px}
                .gallery-card-price{font-size:1.15rem;color:var(--brown-900);font-weight:800;background:#fff6f0;padding:8px 12px;border-radius:10px;border:1px solid rgba(0,0,0,0.04);box-shadow:0 6px 18px rgba(0,0,0,0.08)}
                .gallery-card-btn{background:linear-gradient(180deg,var(--brown-700),var(--brown-600));color:#fff;padding:10px 14px;border-radius:8px;text-decoration:none}

                /* Bottom highlight */
                .highlight-bar{max-width:1100px;margin:30px auto;padding:20px;border-radius:14px;background:linear-gradient(90deg,var(--brown-900),var(--brown-700));color:#fff;box-shadow:0 20px 60px rgba(0,0,0,0.22)}
                .highlight-grid{display:flex;gap:18px;align-items:center;justify-content:space-between;flex-wrap:wrap}
                .highlight-item{display:flex;gap:12px;align-items:center;min-width:160px}
                .highlight-item .icon{width:56px;height:56px;border-radius:10px;background:rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:center}

                /* Footer */
                footer{background:var(--brown-900);color:#f0e6e1;padding:36px 0;margin-top:32px}
                footer a{color:#f8e9df}
                footer .logo{font-family:'Playfair Display',Georgia,serif;font-size:20px;font-weight:700;display:flex;align-items:center;gap:10px}

                /* Responsive */
                @media(max-width:900px){
                    .main-nav{position:static;transform:none;left:auto;top:auto;display:flex;justify-content:center;padding-top:6px}
                    header .header-content{padding:0 6px}
                    .hero{min-height:520px;padding:5.4rem 0 2rem}
                    .hero .hero-inner{padding-left:18px;padding-right:18px;max-width:100%}
                    .hero h1{font-size:2.2rem}
                    .gallery-grid{grid-template-columns:1fr}
                    .gallery-card{flex-direction:column}
                    .gallery-card-img{width:100%;height:220px}
                    .benefit-strip{flex-direction:column;gap:12px}
                    .highlight-grid{flex-direction:column;align-items:stretch}
                    .search-card{flex-direction:column;gap:12px;margin:-36px 16px 18px;padding:14px}
                    .search-card .search-btn{width:100%}
                    footer .container{padding:0 16px}
                    nav{overflow:auto}
                }

                @media(max-width:480px){
                    .hero{min-height:420px;padding:4rem 0 1.6rem}
                    .hero h1{font-size:1.6rem}
                    .subtitle{font-size:12px}
                    .btn-hero{padding:10px 14px}
                    .btn-ghost{padding:8px 10px}
                    .search-card{margin:-24px 12px 14px;padding:12px;border-radius:12px}
                    .gallery-card-img{height:180px}
                    .gallery-card-content{padding:16px}
                }
            </style>

    <body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="brand">
                    <img src="/image/logo.png" alt="logo" style="height:36px;border-radius:6px;margin-right:10px">
                    <div style="line-height:1">
                        <div style="font-size:13px;font-weight:700;opacity:0.95">Druk Wellness</div>
                        <div style="font-size:11px;opacity:0.85;margin-top:1px">Booking System</div>
                    </div>
                </div>

                <nav class="main-nav">
                    <a href="{{ route('home') }}" class="active">Home</a>
                    <a href="{{ route('services.index') }}">Services</a>
                    <a href="#">Wellness Baths</a>
                    <a href="{{ route('howitworks') }}">How It Works</a>
                    <a href="{{ route('about') }}">About Us</a>
                    <a href="{{ route('contact') }}">Contact Us</a>
                </nav>

                <div class="nav-actions">
                    <a href="{{ route('login') }}" class="nav-btn">Login</a>
                    <a href="{{ route('register') }}" class="nav-btn primary">Sign Up</a>
                </div>
            </div>
        </div>
    </header>

    <section class="hero">
        <picture class="hero-bg">
            <source type="image/webp" srcset="/image/optimized/Six%20Senses%20Thimphu-1600.webp 1600w, /image/optimized/Six%20Senses%20Thimphu-1200.webp 1200w, /image/optimized/Six%20Senses%20Thimphu-800.webp 800w" sizes="100vw">
            <img src="/image/Six Senses Thimphu.jpg" alt="Bhutan wellness" loading="lazy" decoding="async">
        </picture>
        <div class="hero-overlay"></div>
        <div class="container hero-inner">
            <div class="subtitle">AUTHENTIC BHUTANESE WELLNESS</div>
            <h1>Relax. Rejuvenate. Restore Your Balance.</h1>
            <p>Discover authentic hot stone baths and wellness services across Bhutan. Easy booking, clear pricing, and trusted wellness experiences.</p>
            <div class="cta-buttons">
               <a href="{{ route('services.index') }}" class="btn-hero">Explore Services ➜</a>
               <a href="{{ route('howitworks') }}" class="btn-ghost">How It Works</a>
            </div>
        </div>
    </section>

    <!-- Floating search card (overlaps hero) -->

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
    <div class="search-card">
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
                    <picture>
                        <source type="image/webp" srcset="/image/optimized/hot%20stone%20bath-1200.webp 1200w, /image/optimized/hot%20stone%20bath-800.webp 800w" sizes="(max-width:900px) 100vw, 50vw">
                        <img src="/image/hot stone bath.png" alt="Hot Stone Bath" class="gallery-card-img" loading="lazy" decoding="async" width="560" height="320">
                    </picture>
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

                <!-- Only two featured services as requested: Hot Stone Bath and Medicinal Water -->
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
                

                <!-- Footer links and contact removed per user request -->
            </div>

            <div style="text-align:center;border-top:1px solid rgba(255,255,255,0.06);padding-top:12px;color:#f0e6e1"></div>
        </div>
    </footer>
</body>
</html>
