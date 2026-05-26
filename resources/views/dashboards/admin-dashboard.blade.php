<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hot Stone Bath System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
            color: #333;
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 200px;
            height: 100vh;
            background: #1a1a2e;
            color: white;
            overflow-y: auto;
            padding: 1.5rem 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.15);
            z-index: 999;
        }

        .sidebar-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1.5rem;
        }

        .sidebar-title {
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-section {
            margin-bottom: 1.5rem;
        }

        .sidebar-section-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #888;
            letter-spacing: 1px;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin: 0.25rem 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            padding-left: 2rem;
        }

        .sidebar-menu a.active {
            background: rgba(102, 126, 234, 0.2);
            color: #667eea;
            border-left: 3px solid #667eea;
            padding-left: 1.2rem;
        }

        .sidebar-menu i {
            font-size: 1rem;
            width: 20px;
        }

        .sidebar-menu button {
            background: none;
            border: none;
            color: rgba(255,255,255,0.7);
            cursor: pointer;
            padding: 0;
            font: inherit;
            width: 100%;
            text-align: left;
            transition: all 0.3s ease;
        }

        .sidebar-menu button:hover {
            color: white;
        }

        /* Main Content */
        .main-wrapper {
            margin-left: 200px;
            width: calc(100% - 200px);
            min-height: 100vh;
            background: #f5f5f5;
        }

        .topbar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #666;
        }

        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1rem;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #333;
        }

        .user-role {
            font-size: 0.75rem;
            color: #999;
        }

        .content-area {
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1a1a2e;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 0.9rem;
            color: #999;
        }

        /* Alert/Welcome Banner */
        .alert-banner {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 1rem 1.5rem;
            border-radius: 6px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .alert-banner i {
            color: #28a745;
            font-size: 1.2rem;
        }

        .alert-banner-text {
            color: #155724;
            font-size: 0.95rem;
            font-weight: 500;
        }

        /* Quick Actions */
        .quick-actions {
            margin-bottom: 2rem;
        }

        .quick-actions-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-boxes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1rem;
        }

        .action-box {
            background: white;
            border: 2px solid;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .action-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .action-box i {
            font-size: 1.8rem;
        }

        .action-box-text {
            font-weight: 600;
            font-size: 0.85rem;
        }

        .action-box.red { border-color: #f5a5a5; color: #d32f2f; }
        .action-box.blue { border-color: #5b9bd5; color: #1976d2; }
        .action-box.green { border-color: #70ad47; color: #388e3c; }
        .action-box.orange { border-color: #f4b084; color: #f57c00; }
        .action-box.purple { border-color: #b4a7d6; color: #7b1fa2; }
        .action-box.teal { border-color: #70ad8c; color: #00796b; }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-box {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-top: 4px solid;
        }

        .stat-box.purple { border-top-color: #764ba2; }
        .stat-box.blue { border-top-color: #667eea; }
        .stat-box.green { border-top-color: #48bb78; }
        .stat-box.orange { border-top-color: #f6ad55; }
        .stat-box.teal { border-top-color: #38b6a8; }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 0.5rem;
        }

        .stat-description {
            font-size: 0.8rem;
            color: #999;
        }

        /* Section Cards */
        .section {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a1a2e;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: #667eea;
        }

        .commission-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .commission-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }

        .commission-card.alt {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        }

        .commission-label {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            font-weight: 600;
        }

        .commission-value {
            font-size: 1.8rem;
            font-weight: 700;
        }

        .commission-description {
            font-size: 0.75rem;
            opacity: 0.8;
            margin-top: 0.5rem;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        thead {
            background: #f8f9fa;
        }

        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #1a1a2e;
            border-bottom: 2px solid #e0e0e0;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.9rem;
        }

        tbody tr:hover {
            background: #fafafa;
        }

        .badge {
            display: inline-block;
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        /* Chart */
        .chart-container {
            position: relative;
            height: 300px;
            margin: 1.5rem 0;
        }

        /* Buttons */
        .btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        .btn-settings {
            background: transparent;
            color: #667eea;
            border: 1px solid #667eea;
        }

        .btn-settings:hover {
            background: #667eea;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }

            .action-boxes {
                grid-template-columns: repeat(2, 1fr);
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-title">
                <img src="/image/logo.png" alt="Hotstone Bath" style="height: 32px; width: auto; object-fit: contain;">
                <span style="margin-left: 0.5rem;">Hotstone Bath</span>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">OVERVIEW</div>
            <ul class="sidebar-menu">
                <li><a href="{{ url('/admin/dashboard') }}" class="active"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            </ul>
        </div>

        <!-- BOOKING REQUESTS SECTION -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">BOOKING REQUESTS</div>
            <div id="pendingRequestsList" style="max-height: 400px; overflow-y: auto;">
                <div style="padding: 0.75rem 1.5rem; color: #888; font-size: 0.85rem;">
                    <i class="fas fa-spinner fa-spin"></i> Loading requests...
                </div>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">USERS</div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.users') }}"><i class="fas fa-users"></i> All Users</a></li>
                <li><a href="{{ route('admin.users') }}?status=pending_verification"><i class="fas fa-user-check"></i> Pending Approvals</a></li>
                <li><a href="{{ route('admin.users') }}?role=owner"><i class="fas fa-store"></i> Owners</a></li>
                <li><a href="{{ route('admin.users') }}?role=guest"><i class="fas fa-user-tie"></i> Tenants</a></li>
            </ul>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">LISTINGS</div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.users') }}?role=owner"><i class="fas fa-bath"></i> All Properties</a></li>
            </ul>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">FINANCE</div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.revenue') }}"><i class="fas fa-credit-card"></i> Transactions</a></li>
                <li><a href="{{ route('admin.bookings') }}"><i class="fas fa-history"></i> Booking Activity</a></li>
            </ul>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">SYSTEM</div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="{{ url('/') }}"><i class="fas fa-globe"></i> Back to Website</a></li>
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST" style="display: block;">
                        @csrf
                        <button type="submit" style="padding: 0.75rem 1.5rem; width: 100%; text-align: left; font-size: 0.9rem; display: flex; align-items: center; gap: 0.75rem;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-wrapper">
        <!-- Top Bar -->
        <div class="topbar">
            <div class="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Admin</a>
                <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
                <span>Dashboard</span>
            </div>
            <div class="topbar-right">
                <div class="user-section">
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                        <div class="user-role">Administrator</div>
                    </div>
                    <div class="user-avatar">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Welcome Banner -->
            <div class="alert-banner">
                <i class="fas fa-check-circle"></i>
                <span class="alert-banner-text">Welcome to Admin Dashboard, {{ Auth::user()->name ?? 'Admin' }}!</span>
            </div>

            <!-- Page Header -->
            <div class="page-header">
                <div class="page-title">
                    <i class="fas fa-chart-line"></i> Dashboard
                </div>
                <div class="page-subtitle">Welcome back, {{ Auth::user()->name ?? 'Admin' }}. {{ now()->format('l, j F Y') }}</div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <div class="quick-actions-title">
                    <i class="fas fa-bolt"></i> Quick Actions
                </div>
                <div class="action-boxes">
                    <a href="{{ route('admin.users') }}?status=pending_verification" class="action-box red">
                        <i class="fas fa-user-check"></i>
                        <span class="action-box-text">Approve Users</span>
                    </a>
                    <a href="{{ route('admin.users') }}?role=owner" class="action-box blue">
                        <i class="fas fa-eye"></i>
                        <span class="action-box-text">Review Properties</span>
                    </a>
                    <a href="{{ route('admin.users') }}?role=owner" class="action-box green">
                        <i class="fas fa-users-cog"></i>
                        <span class="action-box-text">Manage Owners</span>
                    </a>
                    <a href="{{ route('admin.users') }}?role=guest" class="action-box orange">
                        <i class="fas fa-door-open"></i>
                        <span class="action-box-text">Manage Tenants</span>
                    </a>
                    <a href="{{ route('admin.revenue') }}" class="action-box purple">
                        <i class="fas fa-money-bill"></i>
                        <span class="action-box-text">Transactions</span>
                    </a>
                    <a href="{{ route('admin.revenue') }}" class="action-box teal">
                        <i class="fas fa-coins"></i>
                        <span class="action-box-text">Commission 15%</span>
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-box purple">
                    <i class="fas fa-coins" style="font-size: 1.5rem; color: #764ba2; margin-bottom: 0.5rem;"></i>
                    <div class="stat-value">Nu. {{ number_format($totalCommission ?? 0) }}</div>
                    <div class="stat-label">Platform Commission</div>
                    <div class="stat-description">15% of all payments</div>
                </div>

                <div class="stat-box blue">
                    <i class="fas fa-money-bill-wave" style="font-size: 1.5rem; color: #667eea; margin-bottom: 0.5rem;"></i>
                    <div class="stat-value">Nu. {{ number_format($totalRevenue ?? 0) }}</div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-description">All bookings</div>
                </div>

                <div class="stat-box green">
                    <i class="fas fa-store" style="font-size: 1.5rem; color: #48bb78; margin-bottom: 0.5rem;"></i>
                    <div class="stat-value">{{ $totalOwners ?? 0 }}</div>
                    <div class="stat-label">Total Owners</div>
                    <div class="stat-description">Approved accounts</div>
                </div>

                <div class="stat-box orange">
                    <i class="fas fa-calendar-check" style="font-size: 1.5rem; color: #f6ad55; margin-bottom: 0.5rem;"></i>
                    <div class="stat-value">{{ $totalBookings ?? 0 }}</div>
                    <div class="stat-label">Total Bookings</div>
                    <div class="stat-description">All reservations</div>
                </div>

                <div class="stat-box teal">
                    <i class="fas fa-bath" style="font-size: 1.5rem; color: #38b6a8; margin-bottom: 0.5rem;"></i>
                    <div class="stat-value">{{ $totalListings ?? 0 }}</div>
                    <div class="stat-label">Total Properties</div>
                    <div class="stat-description">All listings</div>
                </div>
            </div>

            <!-- Commission Overview Section -->
            <div class="section">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fas fa-coins"></i> Commission Overview
                    </div>
                    <button class="btn btn-settings">
                        <i class="fas fa-cog"></i> Settings
                    </button>
                </div>

                <div class="commission-overview">
                    <div class="commission-card">
                        <div class="commission-label">TOTAL COMMISSION</div>
                        <div class="commission-value">Nu. {{ number_format($totalCommission ?? 0) }}</div>
                        <div class="commission-description">Year to date earnings</div>
                    </div>

                    <div class="commission-card">
                        <div class="commission-label">THIS MONTH</div>
                        <div class="commission-value">Nu. {{ number_format($monthlyCommission ?? 0) }}</div>
                        <div class="commission-description">Current month earnings</div>
                    </div>

                    <div class="commission-card">
                        <div class="commission-label">OWNER NET PAYOUT</div>
                        <div class="commission-value">Nu. {{ number_format(($totalRevenue ?? 0) - ($totalCommission ?? 0)) }}</div>
                        <div class="commission-description">After commission deduction</div>
                    </div>

                    <div class="commission-card alt">
                        <div class="commission-label">COMMISSION RATE</div>
                        <div class="commission-value">15%</div>
                        <div class="commission-description">Standard platform rate</div>
                    </div>
                </div>

                <!-- Chart -->
                <h3 style="margin: 1.5rem 0 1rem; font-size: 1rem; font-weight: 600; color: #1a1a2e;">
                    <i class="fas fa-chart-bar"></i> Monthly Revenue & Commission (Last 12 Months)
                </h3>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Top Owners Section -->
            <div class="section">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fas fa-trophy"></i> Top Owners by Revenue
                    </div>
                    <a href="{{ route('admin.revenue') }}" style="font-size: 0.85rem; color: #667eea; text-decoration: none;">View All</a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>OWNER</th>
                            <th>REVENUE</th>
                            <th>COMMISSION</th>
                            <th>BOOKINGS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topOwners ?? [] as $owner)
                            <tr>
                                <td><strong>{{ $owner['name'] ?? 'N/A' }}</strong></td>
                                <td>Nu. {{ number_format($owner['revenue'] ?? 0) }}</td>
                                <td style="color: #667eea; font-weight: 600;">Nu. {{ number_format(($owner['revenue'] ?? 0) * 0.15) }}</td>
                                <td>{{ $owner['bookings_count'] ?? 0 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: #999; padding: 2rem;">No owner data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Recent Bookings Section -->
            <div class="section">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fas fa-calendar-check"></i> Recent Bookings
                    </div>
                    <a href="{{ route('admin.bookings') }}" style="font-size: 0.85rem; color: #667eea; text-decoration: none;">View All</a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>BOOKING ID</th>
                            <th>GUEST</th>
                            <th>PROPERTY</th>
                            <th>AMOUNT</th>
                            <th>COMMISSION</th>
                            <th>STATUS</th>
                            <th>DATE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings ?? [] as $booking)
                            <tr>
                                <td>#{{ $booking['id'] ?? 'N/A' }}</td>
                                <td>{{ $booking['user']->name ?? 'N/A' }}</td>
                                <td>{{ $booking['bath']->name ?? 'N/A' }}</td>
                                <td>Nu. {{ number_format($booking['amount'] ?? 0) }}</td>
                                <td style="color: #667eea; font-weight: 600;">Nu. {{ number_format(($booking['amount'] ?? 0) * 0.15) }}</td>
                                <td>
                                    @if($booking['status'] == 'completed')
                                        <span class="badge badge-success">Completed</span>
                                    @elseif($booking['status'] == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-info">{{ ucfirst($booking['status'] ?? 'Unknown') }}</span>
                                    @endif
                                </td>
                                <td>{{ $booking['created_at']->format('M d, Y') ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; color: #999; padding: 2rem;">No booking data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pending Service Approvals Section -->
            <div class="section" id="pending-services">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fas fa-concierge-bell"></i> Pending Service Approvals
                    </div>
                    <span style="font-size: 0.85rem; color: #667eea; font-weight: 600;">
                        {{ $pendingServices->count() }} pending
                    </span>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>SERVICE</th>
                            <th>BATH</th>
                            <th>OWNER</th>
                            <th>DZONGKHAG</th>
                            <th>PRICE</th>
                            <th>DURATION</th>
                            <th>SUBMITTED</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingServices ?? [] as $service)
                            <tr>
                                <td><strong>{{ $service->service_type }}</strong></td>
                                <td>{{ optional($service->bath)->name ?? 'N/A' }}</td>
                                <td>{{ optional(optional($service->bath)->owner)->name ?? 'N/A' }}</td>
                                <td>{{ optional(optional($service->bath)->dzongkhag)->name ?? 'N/A' }}</td>
                                <td>Nu. {{ number_format((float) $service->price, 2) }}</td>
                                <td>{{ $service->duration_minutes }}m</td>
                                <td>{{ optional($service->created_at)->format('M d, Y H:i') }}</td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                        <form method="POST" action="{{ route('admin.service.approve', $service) }}">
                                            @csrf
                                            <button type="submit" class="btn" style="background: #48bb78;">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.service.reject', $service) }}">
                                            @csrf
                                            <input type="hidden" name="reason" value="Rejected by admin review">
                                            <button type="submit" class="btn" style="background: #e53e3e;">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; color: #999; padding: 2rem;">No services pending approval.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Load pending booking requests
        function loadPendingRequests() {
            fetch('/admin/messages/pending-requests')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const container = document.getElementById('pendingRequestsList');
                    
                    if (!data || (Array.isArray(data) && data.length === 0)) {
                        container.innerHTML = '<div style="padding: 0.75rem 1.5rem; color: #888; font-size: 0.85rem;"><i class="fas fa-inbox"></i> No pending requests</div>';
                        return;
                    }

                    let html = '<ul class="sidebar-menu">';
                    
                    // Handle both array and object responses
                    const bookings = Array.isArray(data) ? data : [];
                    
                    if (bookings.length === 0) {
                        container.innerHTML = '<div style="padding: 0.75rem 1.5rem; color: #888; font-size: 0.85rem;"><i class="fas fa-inbox"></i> No pending requests</div>';
                        return;
                    }

                    bookings.forEach(booking => {
                        try {
                            const guestName = booking.guest?.name || booking.guest_name || 'Unknown';
                            const bathName = booking.bath?.name || 'Unknown Bath';
                            const unreadCount = booking.messages?.length || 0;
                            const badgeHTML = unreadCount > 0 ? `<span style="display: inline-block; background: #ff6b6b; color: white; border-radius: 50%; width: 20px; height: 20px; line-height: 20px; text-align: center; font-size: 0.7rem; margin-left: 0.25rem;">${unreadCount}</span>` : '';
                            
                            html += `
                                <li>
                                    <a href="/admin/messages/${booking.id}" style="justify-content: space-between; padding-right: 0.75rem;">
                                        <div style="flex: 1;">
                                            <div style="font-size: 0.85rem; font-weight: 500;">${guestName}</div>
                                            <div style="font-size: 0.75rem; color: rgba(255,255,255,0.6);">${bathName}</div>
                                        </div>
                                        ${badgeHTML}
                                    </a>
                                </li>
                            `;
                        } catch (e) {
                            console.error('Error processing booking:', booking, e);
                        }
                    });
                    html += '</ul>';
                    container.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading pending requests:', error);
                    const container = document.getElementById('pendingRequestsList');
                    container.innerHTML = `
                        <div style="padding: 0.75rem 1.5rem; color: #e74c3c; font-size: 0.85rem;">
                            <i class="fas fa-exclamation-circle"></i> Error loading requests<br>
                            <small style="color: #999;">${error.message}</small>
                        </div>
                    `;
                });
        }

        // Load pending requests on page load
        document.addEventListener('DOMContentLoaded', loadPendingRequests);
        
        // Refresh pending requests every 10 seconds
        setInterval(loadPendingRequests, 10000);

        // Revenue Chart
        const ctx = document.getElementById('revenueChart');
        if (ctx) {
            const revenueChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    datasets: [
                        {
                            label: 'Revenue',
                            data: [45000, 52000, 58000, 62000, 68000, 72000, 75000, 78000, 80000, 82000, 85000, 88000],
                            backgroundColor: '#4472c4',
                            borderRadius: 4
                        },
                        {
                            label: 'Commission',
                            data: [6750, 7800, 8700, 9300, 10200, 10800, 11250, 11700, 12000, 12300, 12750, 13200],
                            backgroundColor: '#70ad47',
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Nu. ' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
