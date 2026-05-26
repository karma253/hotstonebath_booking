@extends('web.layouts.app')

@section('title', 'Bookings Management - Admin')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1">📅 Bookings Management</h1>
            <p class="text-muted">View and manage all customer bookings</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="card card-shadow rounded-4 mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">🔍 Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.bookings') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request()->input('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request()->input('end_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Owner</label>
                    <select name="owner_id" class="form-select">
                        <option value="">All Owners</option>
                        @foreach($owners as $owner)
                            <option value="{{ $owner->id }}" {{ request()->input('owner_id') == $owner->id ? 'selected' : '' }}>
                                {{ $owner->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Payment Status</label>
                    <select name="payment_status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request()->input('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request()->input('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ request()->input('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.bookings') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card card-shadow rounded-4">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Bookings List ({{ $bookings->total() }} total)</h5>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Owner</th>
                            <th>Bath Name</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Payment Status</th>
                            <th>Booking Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>
                                    <strong title="{{ $booking->booking_id }}">{{ substr($booking->booking_id, 0, 8) }}...</strong>
                                </td>
                                <td>
                                    <small>{{ $booking->guest->name ?? $booking->guest_name }}</small>
                                </td>
                                <td>
                                    <small>{{ optional($booking->bath)->owner->name }}</small>
                                </td>
                                <td>
                                    <small>{{ optional($booking->bath)->name }}</small>
                                </td>
                                <td>
                                    <small>{{ $booking->booking_date->format('M d, Y') }} @ {{ $booking->start_time }}</small>
                                </td>
                                <td>
                                    <strong>Nu. {{ number_format($booking->total_price, 2) }}</strong>
                                </td>
                                <td>
                                    @if($booking->payment_status === 'paid')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Paid
                                        </span>
                                    @elseif($booking->payment_status === 'pending')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-hourglass-half"></i> Pending
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-exclamation-circle"></i> {{ ucfirst($booking->payment_status) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->status === 'confirmed')
                                        <span class="badge bg-info">
                                            <i class="fas fa-calendar-check"></i> Confirmed
                                        </span>
                                    @elseif($booking->status === 'completed')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Completed
                                        </span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-ban"></i> Cancelled
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                                    <p>No bookings found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $bookings->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    .card-shadow {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .rounded-4 {
        border-radius: 12px;
    }
</style>
@endsection
