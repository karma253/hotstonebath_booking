<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - Hot Stone Bath</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            color: #667eea;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            color: #667eea;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .stat-card .number {
            font-size: 2rem;
            color: #333;
            font-weight: bold;
        }

        .section {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .section h2 {
            color: #667eea;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 0.5rem;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .btn {
            background: #667eea;
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
            background: #764ba2;
        }

        .btn-secondary {
            background: #e74c3c;
        }

        .btn-secondary:hover {
            background: #c0392b;
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

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .demo-notice {
            background: #cee5ff;
            border: 1px solid #99c9ff;
            color: #004085;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .demo-notice strong {
            color: #002752;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>🛁 Owner Dashboard</h1>
            <a href="/?logout=true" class="logout-btn">Logout</a>
        </div>
    </header>

    <div class="container">
        <div class="demo-notice">
            <strong>Demo Mode:</strong> You are logged in as an owner. This dashboard is a sample interface showing what owners can do.
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Active Baths</h3>
                <div class="number">1</div>
            </div>
            <div class="stat-card">
                <h3>Total Bookings</h3>
                <div class="number">24</div>
            </div>
            <div class="stat-card">
                <h3>Pending Bookings</h3>
                <div class="number">3</div>
            </div>
            <div class="stat-card">
                <h3>Revenue (Month)</h3>
                <div class="number">₹12,450</div>
            </div>
        </div>

        <div class="section">
            <h2>Bath Management</h2>
            <p>Manage your hot stone bath property details, services, facilities, and images.</p>
            <div class="action-buttons">
                <button class="btn">View Bath Details</button>
                <button class="btn">Edit Services</button>
                <button class="btn">Update Availability</button>
                <button class="btn">Upload Images</button>
            </div>
        </div>

        <div class="section">
            <h2>Bookings</h2>
            <p>Manage incoming bookings and guest requests.</p>

            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Guest Name</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#BK001</td>
                        <td>John Doe</td>
                        <td>2026-03-20</td>
                        <td><span class="status-badge status-pending">Pending</span></td>
                        <td><button class="btn" style="font-size: 0.8rem; padding: 0.5rem 1rem;">Confirm</button></td>
                    </tr>
                    <tr>
                        <td>#BK002</td>
                        <td>Jane Smith</td>
                        <td>2026-03-22</td>
                        <td><span class="status-badge status-active">Confirmed</span></td>
                        <td><button class="btn" style="font-size: 0.8rem; padding: 0.5rem 1rem;">View</button></td>
                    </tr>
                </tbody>
            </table>

            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button class="btn">View All Bookings</button>
                <button class="btn">View Reports</button>
            </div>
        </div>

        <div class="section">
            <h2>Reviews</h2>
            <p>Guest ratings and feedback for your bath property.</p>
            <div style="background: #f9f9f9; padding: 1rem; border-radius: 5px; margin-top: 1rem;">
                <p><strong>⭐ 4.8/5</strong> - Based on 12 guest reviews</p>
                <p style="margin-top: 0.5rem; color: #666;">"Excellent experience! Clean facilities and great service."</p>
            </div>
        </div>
    </div>
</body>
</html>
