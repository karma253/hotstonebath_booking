<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Dashboard - Hot Stone Bath</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        header {
            background: linear-gradient(135deg, #48dbfb 0%, #0abde3 100%);
            color: white;
            padding: 1.5rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-content h1 {
            font-size: 1.5rem;
        }

        .logout-btn {
            background: white;
            color: #0abde3;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }

        .logout-btn:hover {
            background: #f0f0f0;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
        }

        .tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid #ddd;
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 1rem;
            cursor: pointer;
            color: #666;
            font-size: 1rem;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }

        .tab-btn.active {
            color: #0abde3;
            border-bottom-color: #0abde3;
        }

        .tab-btn:hover {
            color: #0abde3;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .section {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .section h2 {
            color: #0abde3;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #0abde3;
            padding-bottom: 0.5rem;
        }

        .search-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .btn {
            background: #0abde3;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #048ba8;
        }

        .btn-secondary {
            background: #666;
        }

        .btn-secondary:hover {
            background: #333;
        }

        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        .baths-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .bath-card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .bath-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .bath-image {
            background: linear-gradient(135deg, #48dbfb 0%, #0abde3 100%);
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }

        .bath-info {
            padding: 1.5rem;
        }

        .bath-name {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .bath-location {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .bath-rating {
            color: #f39c12;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .bath-price {
            color: #0abde3;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .bath-actions {
            display: flex;
            gap: 0.5rem;
        }

        .bath-actions button {
            flex: 1;
            padding: 0.5rem;
            font-size: 0.85rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        table th {
            background: #f5f5f5;
            padding: 1rem;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #ddd;
        }

        table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
        }

        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #666;
        }

        .empty-state p {
            margin-bottom: 1rem;
        }

        .demo-notice {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .demo-notice strong {
            color: #051c26;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>🛁 Guest Dashboard</h1>
            <a href="/?logout=true" class="logout-btn">Logout</a>
        </div>
    </header>

    <div class="container">
        <div class="demo-notice">
            <strong>Demo Mode:</strong> Logged in as a guest. Browse baths, make bookings, and leave reviews.
        </div>

        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('search')">🔍 Search Baths</button>
            <button class="tab-btn" onclick="switchTab('bookings')">📅 My Bookings</button>
            <button class="tab-btn" onclick="switchTab('reviews')">⭐ My Reviews</button>
            <button class="tab-btn" onclick="switchTab('profile')">👤 Profile</button>
        </div>

        <!-- Search Baths Tab -->
        <div id="search" class="tab-content active">
            <div class="section">
                <h2>Search Hot Stone Baths</h2>

                <div class="search-form">
                    <div class="form-group">
                        <label for="location">Location (Dzongkhag)</label>
                        <select id="location">
                            <option>Select location</option>
                            <option>Thimphu</option>
                            <option>Paro</option>
                            <option>Punakha</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" id="date">
                    </div>
                    <div class="form-group">
                        <label for="guests">Number of Guests</label>
                        <input type="number" id="guests" min="1" value="1">
                    </div>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button class="btn">Search</button>
                    </div>
                </div>

                <div class="baths-grid">
                    <div class="bath-card">
                        <div class="bath-image">🛁</div>
                        <div class="bath-info">
                            <div class="bath-name">Paro Hot Springs</div>
                            <div class="bath-location">📍 Paro Dzongkhag</div>
                            <div class="bath-rating">⭐ 4.8 (24 reviews)</div>
                            <div class="bath-price">₹800 / per person</div>
                            <div class="bath-actions">
                                <button class="btn btn-small">View Details</button>
                                <button class="btn btn-secondary btn-small">Book</button>
                            </div>
                        </div>
                    </div>

                    <div class="bath-card">
                        <div class="bath-image">♨️</div>
                        <div class="bath-info">
                            <div class="bath-name">Thimphu Hot Bath</div>
                            <div class="bath-location">📍 Thimphu Dzongkhag</div>
                            <div class="bath-rating">⭐ 4.5 (18 reviews)</div>
                            <div class="bath-price">₹600 / per person</div>
                            <div class="bath-actions">
                                <button class="btn btn-small">View Details</button>
                                <button class="btn btn-secondary btn-small">Book</button>
                            </div>
                        </div>
                    </div>

                    <div class="bath-card">
                        <div class="bath-image">🌊</div>
                        <div class="bath-info">
                            <div class="bath-name">Punakha Bath House</div>
                            <div class="bath-location">📍 Punakha Dzongkhag</div>
                            <div class="bath-rating">⭐ 4.9 (31 reviews)</div>
                            <div class="bath-price">₹750 / per person</div>
                            <div class="bath-actions">
                                <button class="btn btn-small">View Details</button>
                                <button class="btn btn-secondary btn-small">Book</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Bookings Tab -->
        <div id="bookings" class="tab-content">
            <div class="section">
                <h2>My Bookings</h2>

                <table>
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Bath Name</th>
                            <th>Date & Time</th>
                            <th>Guests</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#GB001</td>
                            <td>Paro Hot Springs</td>
                            <td>2026-03-25, 10:00 AM</td>
                            <td>2</td>
                            <td><span class="status-badge status-confirmed">Confirmed</span></td>
                            <td><button class="btn btn-small">View</button></td>
                        </tr>
                        <tr>
                            <td>#GB002</td>
                            <td>Thimphu Hot Bath</td>
                            <td>2026-03-28, 2:00 PM</td>
                            <td>1</td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td><button class="btn btn-small">Cancel</button></td>
                        </tr>
                        <tr>
                            <td>#GB003</td>
                            <td>Punakha Bath House</td>
                            <td>2026-03-15, 11:00 AM</td>
                            <td>3</td>
                            <td><span class="status-badge status-completed">Completed</span></td>
                            <td><button class="btn btn-secondary btn-small">Review</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- My Reviews Tab -->
        <div id="reviews" class="tab-content">
            <div class="section">
                <h2>My Reviews</h2>

                <div style="background: #f9f9f9; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <div>
                            <div style="font-weight: bold;">Punakha Bath House</div>
                            <div style="color: #666; font-size: 0.9rem;">Reviewed on March 15, 2026</div>
                        </div>
                        <div style="color: #f39c12; font-size: 1.2rem;">⭐⭐⭐⭐⭐</div>
                    </div>
                    <p>"Amazing experience! The staff was very friendly and the facilities are immaculate. Highly recommended!"</p>
                </div>

                <div style="background: #f9f9f9; padding: 1.5rem; border-radius: 8px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <div>
                            <div style="font-weight: bold;">Paro Hot Springs</div>
                            <div style="color: #666; font-size: 0.9rem;">Reviewed on March 10, 2026</div>
                        </div>
                        <div style="color: #f39c12; font-size: 1.2rem;">⭐⭐⭐⭐</div>
                    </div>
                    <p>"Great bath experience with beautiful surroundings. Would definitely visit again."</p>
                </div>
            </div>
        </div>

        <!-- Profile Tab -->
        <div id="profile" class="tab-content">
            <div class="section">
                <h2>My Profile</h2>

                <div style="max-width: 500px;">
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label>Full Name</label>
                        <input type="text" value="Tenzin Wangmo" disabled style="background: #f5f5f5;">
                    </div>
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label>Email</label>
                        <input type="email" value="guest@example.com" disabled style="background: #f5f5f5;">
                    </div>
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label>Phone</label>
                        <input type="tel" value="+975-1234567" disabled style="background: #f5f5f5;">
                    </div>
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label>Member Since</label>
                        <input type="text" value="January 2026" disabled style="background: #f5f5f5;">
                    </div>
                    <button class="btn">Edit Profile</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.remove('active'));

            // Remove active class from all buttons
            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(button => button.classList.remove('active'));

            // Show selected tab
            document.getElementById(tabName).classList.add('active');

            // Add active class to clicked button
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
