<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hot Stone Bath Booking System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        header {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 2rem;
            transition: opacity 0.3s;
        }

        nav a:hover {
            opacity: 0.8;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-primary {
            background: white;
            color: #e74c3c;
            font-weight: bold;
        }

        .btn-primary:hover {
            background: #f5f5f5;
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: #e74c3c;
        }

        .hero {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 6rem 0;
            text-align: center;
            margin-bottom: 3rem;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

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
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
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
        }

        .doc-item a:hover {
            text-decoration: underline;
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
            color: #e74c3c;
            margin-bottom: 2rem;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .gallery-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            height: 250px;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-text {
            color: white;
            text-align: center;
        }

        .gallery-text h3 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }

        .gallery-text p {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">🛁 Hot Stone Bath</div>
                <nav>
                    <a href="#features">Features</a>
                </nav>
                <div class="nav-buttons">
                    <a href="/guest/login" class="btn btn-secondary">Guest Login</a>
                </div>
            </div>
        </div>
    </header>

    <div class="hero">
        <div class="container">
            <h1>Hot Stone Bath Booking System</h1>
            <p>Discover and book authentic hot stone bath experiences</p>
            <div class="cta-buttons">
                <a href="/guest/login" class="btn btn-primary btn-large">Browse & Book</a>
            </div>
        </div>
    </div>

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
                <p>Support for all 17 Bhutanese Dzongkhags with localized content and pricing</p>
            </div>
        </section>

    </div>

    <section class="gallery">
        <div class="container">
            <h2>Featured Hot Stone Baths</h2>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1544161515-81aae3ff8d23?w=400&h=300&fit=crop" alt="Luxury Bath">
                    <div class="gallery-overlay">
                        <div class="gallery-text">
                            <h3>Luxury Bath House</h3>
                            <p>Premium hot stone spa experience</p>
                        </div>
                    </div>
                </div>

                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1566146272354-dc73f72a0d69?w=400&h=300&fit=crop" alt="Natural Hot Springs">
                    <div class="gallery-overlay">
                        <div class="gallery-text">
                            <h3>Natural Hot Springs</h3>
                            <p>Authentic mountain spa retreat</p>
                        </div>
                    </div>
                </div>

                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1540550191990-ca2968213b11?w=400&h=300&fit=crop" alt="Wellness Center">
                    <div class="gallery-overlay">
                        <div class="gallery-text">
                            <h3>Wellness Center</h3>
                            <p>Traditional healing bath treatments</p>
                        </div>
                    </div>
                </div>

                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=400&h=300&fit=crop" alt="Modern Spa">
                    <div class="gallery-overlay">
                        <div class="gallery-text">
                            <h3>Modern Spa Resort</h3>
                            <p>Contemporary hot bath facility</p>
                        </div>
                    </div>
                </div>

                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1519046904884-53103b34b206?w=400&h=300&fit=crop" alt="Mountain Retreat">
                    <div class="gallery-overlay">
                        <div class="gallery-text">
                            <h3>Mountain Retreat</h3>
                            <p>Scenic landscape hot stone baths</p>
                        </div>
                    </div>
                </div>

                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1545069975-e3b88e26a39f?w=400&h=300&fit=crop" alt="Tranquil Spa">
                    <div class="gallery-overlay">
                        <div class="gallery-text">
                            <h3>Tranquil Spa</h3>
                            <p>Peaceful relaxation experience</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="container">
            <p>&copy; 2024 Hot Stone Bath Booking System</p>
        </div>
    </footer>
</body>
</html>
