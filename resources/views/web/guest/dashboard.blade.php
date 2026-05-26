@extends('web.layouts.app')

@section('title', 'Guest Dashboard')

@section('content')
<style>
    .dashboard-container {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 2rem;
        margin-top: 2rem;
    }

    .sidebar {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .sidebar-header {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        padding: 1.5rem;
        text-align: center;
    }

    .sidebar-header h5 {
        margin: 0 0 0.25rem 0;
    }

    .sidebar-header p {
        margin: 0;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-menu li {
        border-bottom: 1px solid #f0f0f0;
    }

    .sidebar-menu li:last-child {
        border-bottom: none;
    }

    .sidebar-menu a {
        display: block;
        padding: 1rem 1.5rem;
        color: #333;
        text-decoration: none;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .sidebar-menu a:hover {
        background: #f8f9fa;
        color: #e74c3c;
        padding-left: 2rem;
    }

    .sidebar-menu a.active {
        background: #f8f9fa;
        color: #e74c3c;
        border-left: 4px solid #e74c3c;
        padding-left: 1.25rem;
    }

    .sidebar-menu i {
        width: 20px;
        text-align: center;
    }

    .logout-btn {
        border-top: 1px solid #f0f0f0;
        padding: 1rem 1.5rem !important;
        color: #dc3545 !important;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .logout-btn:hover {
        background: #fff5f5;
        padding-left: 2rem !important;
    }

    .main-content {
        padding-bottom: 2rem;
    }

    .welcome-card {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        border-radius: 8px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .welcome-card h2 {
        margin: 0 0 0.5rem 0;
        font-size: 2rem;
    }

    .welcome-card p {
        margin: 0;
        opacity: 0.95;
        font-size: 1.05rem;
    }

    .booking-card {
        background: white;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s;
    }

    .booking-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .booking-card-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 1rem;
    }

    .booking-name {
        font-size: 1.3rem;
        color: #2c3e50;
        font-weight: bold;
        margin: 0;
    }

    .booking-id {
        font-size: 0.85rem;
        color: #999;
    }

    .booking-status {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .booking-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1rem;
    }

    .detail-item {
        padding-bottom: 1rem;
    }

    .detail-label {
        font-size: 0.85rem;
        color: #999;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        font-size: 1.1rem;
        color: #2c3e50;
        font-weight: 600;
    }

    .booking-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #f0f0f0;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .empty-state i {
        font-size: 3rem;
        color: #ddd;
        margin-bottom: 1rem;
    }

    .empty-state h4 {
        color: #999;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #bbb;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .dashboard-container {
            grid-template-columns: 1fr;
        }

        .sidebar {
            position: static;
        }

        .sidebar-menu a, .logout-btn {
            padding-left: 1rem;
        }

        .sidebar-menu a:hover, .logout-btn:hover {
            padding-left: 1rem;
        }

        .booking-details {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h5>{{ Auth::user()->name }}</h5>
            <p>{{ Auth::user()->email }}</p>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('guest.dashboard') }}" class="active">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('guest.dashboard') }}#bookings">
                    <i class="fas fa-calendar"></i> My Bookings
                </a>
            </li>
            <li>
                <a href="{{ route('home') }}">
                    <i class="fas fa-search"></i> Browse Baths
                </a>
            </li>
            <li>
                <a href="{{ route('guest.dashboard') }}#profile">
                    <i class="fas fa-user"></i> Profile
                </a>
            </li>
        </ul>

        <form method="POST" action="{{ route('guest.logout') }}" style="border: none;">
            @csrf
            <button type="submit" class="sidebar-menu logout-btn" style="width: 100%; text-align: left; border: 1px solid transparent;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Pending Approval Alert -->
        @if(Auth::user()->status === 'pending_verification')
            <div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-bottom: 2rem;">
                <i class="fas fa-hourglass-half me-2"></i>
                <strong>Account Pending Approval</strong> - Your account is currently under review by our admin team. You will be able to book baths once your account is approved. Thank you for your patience!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif(Auth::user()->status === 'rejected')
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 2rem;">
                <i class="fas fa-times-circle me-2"></i>
                <strong>Account Rejected</strong> - Your account registration has been rejected. 
                @if(Auth::user()->rejection_reason)
                    <br>Reason: {{ Auth::user()->rejection_reason }}
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Welcome Section -->
        <div class="welcome-card">
            <h2>Welcome, {{ Auth::user()->name }}! 👋</h2>
            <p>Manage your hot stone bath bookings and explore new experiences</p>
        </div>

        <!-- My Bookings Section -->
        <section id="bookings">
            <h3 class="mb-4">
                <i class="fas fa-calendar-check" style="color: #e74c3c; margin-right: 0.5rem;"></i>
                My Bookings
            </h3>

            @if($bookings->count() > 0)
                @foreach($bookings as $booking)
                    <div class="booking-card">
                        <div class="booking-card-header">
                            <div>
                                <p class="booking-name">{{ optional($booking->bath)->name }}</p>
                                <p class="booking-id">Booking ID: {{ $booking->booking_id }}</p>
                            </div>
                            <div class="booking-status">
                                <span class="badge bg-{{ strtolower($booking->payment_status) === 'pending' ? 'warning' : 'success' }}">
                                    <i class="fas fa-money-bill-wave me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $booking->payment_status)) }}
                                </span>
                                <span class="badge bg-{{ in_array($booking->status, ['confirmed', 'completed']) ? 'success' : ($booking->status === 'cancelled' ? 'danger' : 'warning') }}">
                                    <i class="fas fa-check-circle me-1"></i>
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="booking-details">
                            <div class="detail-item">
                                <div class="detail-label">📅 Date</div>
                                <div class="detail-value">{{ optional($booking->booking_date)->format('d M Y') }}</div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-label">⏱️ Time</div>
                                <div class="detail-value">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-label">👥 Guests</div>
                                <div class="detail-value">{{ $booking->number_of_guests }} {{ $booking->number_of_guests === 1 ? 'Person' : 'Persons' }}</div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-label">💰 Total Price</div>
                                <div class="detail-value" style="color: #e74c3c;">Nu. {{ number_format((float) $booking->total_price, 2) }}</div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-label">📍 Location</div>
                                <div class="detail-value">{{ optional(optional($booking->bath)->dzongkhag)->name }}</div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-label">💳 Payment</div>
                                <div class="detail-value">{{ ucfirst(str_replace('_', ' ', $booking->payment_method)) }}</div>
                            </div>
                        </div>

                        @if($booking->special_requests)
                            <div style="background: #f8f9fa; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                                <strong style="color: #666;">Special Requests:</strong>
                                <p style="margin: 0.5rem 0 0 0; color: #666; white-space: pre-wrap;">{{ $booking->special_requests }}</p>
                            </div>
                        @endif

                        <div class="booking-actions">
                            <a href="{{ route('guest.booking.summary', $booking) }}" class="btn btn-sm btn-outline-dark">
                                <i class="fas fa-eye me-1"></i> View Details
                            </a>
                            @if(!in_array($booking->status, ['cancelled', 'completed']))
                                <form method="POST" action="{{ route('guest.booking.cancel', $booking) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                        <i class="fas fa-times me-1"></i> Cancel Booking
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach

                <div class="mt-4">{{ $bookings->links() }}</div>
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h4>No Bookings Yet</h4>
                    <p>You haven't made any bookings yet. Start exploring hot stone baths!</p>
                    <a href="{{ route('home') }}" class="btn btn-dark">
                        <i class="fas fa-search me-2"></i> Browse Hot Stone Baths
                    </a>
                </div>
            @endif
        </section>

        <!-- Profile Section -->
        <section id="profile" style="margin-top: 3rem;">
            <h3 class="mb-4">
                <i class="fas fa-user-circle" style="color: #e74c3c; margin-right: 0.5rem;"></i>
                Profile Information
            </h3>

            <div class="card">
                <div class="card-body" style="background: white; border-radius: 8px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                        <div>
                            <label style="color: #999; font-size: 0.85rem; text-transform: uppercase; font-weight: 600;">Full Name</label>
                            <p style="font-size: 1.1rem; color: #2c3e50; margin: 0.5rem 0 0 0;">{{ Auth::user()->name }}</p>
                        </div>

                        <div>
                            <label style="color: #999; font-size: 0.85rem; text-transform: uppercase; font-weight: 600;">Email Address</label>
                            <p style="font-size: 1.1rem; color: #2c3e50; margin: 0.5rem 0 0 0;">{{ Auth::user()->email }}</p>
                        </div>

                        <div>
                            <label style="color: #999; font-size: 0.85rem; text-transform: uppercase; font-weight: 600;">Phone Number</label>
                            <p style="font-size: 1.1rem; color: #2c3e50; margin: 0.5rem 0 0 0;">{{ Auth::user()->phone ?? 'Not provided' }}</p>
                        </div>

                        <div>
                            <label style="color: #999; font-size: 0.85rem; text-transform: uppercase; font-weight: 600;">Address</label>
                            <p style="font-size: 1.1rem; color: #2c3e50; margin: 0.5rem 0 0 0;">{{ Auth::user()->address ?? 'Not provided' }}</p>
                        </div>

                        <div>
                            <label style="color: #999; font-size: 0.85rem; text-transform: uppercase; font-weight: 600;">Member Since</label>
                            <p style="font-size: 1.1rem; color: #2c3e50; margin: 0.5rem 0 0 0;">{{ Auth::user()->created_at->format('d M Y') }}</p>
                        </div>

                        <div>
                            <label style="color: #999; font-size: 0.85rem; text-transform: uppercase; font-weight: 600;">Total Bookings</label>
                            <p style="font-size: 1.1rem; color: #2c3e50; margin: 0.5rem 0 0 0;">
                                {{ Auth::user()->bookings->count() }} booking{{ Auth::user()->bookings->count() !== 1 ? 's' : '' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

@endsection
