@extends('web.layouts.app')

@section('title', 'Owner Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Owner Dashboard</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('owner.listing.form') }}" class="btn btn-outline-dark">Manage Listing</a>
        <form method="POST" action="{{ route('owner.logout') }}">
            @csrf
            <button class="btn btn-dark">Logout</button>
        </form>
    </div>
</div>

@if(auth()->user()->status === 'pending_verification')
    <div class="alert alert-warning">
        Your account is pending admin verification. You can still update your listing details.
    </div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card card-shadow rounded-4">
            <div class="card-body">
                <h6 class="text-muted">Bath Name</h6>
                <p class="h5 mb-0">{{ $bath?->name ?? 'Not Added Yet' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-shadow rounded-4">
            <div class="card-body">
                <h6 class="text-muted">Listing Status</h6>
                <p class="h5 mb-0 text-capitalize">{{ str_replace('_', ' ', $bath?->status ?? 'pending_verification') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-shadow rounded-4">
            <div class="card-body">
                <h6 class="text-muted">Price / Session</h6>
                <p class="h5 mb-0">Nu. {{ number_format((float)($bath?->price_per_session ?? 0), 2) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card card-shadow rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Guests</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->booking_id }}</td>
                        <td>{{ $booking->guest_name }}</td>
                        <td>{{ optional($booking->booking_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</td>
                        <td>{{ $booking->number_of_guests }}</td>
                        <td>Nu. {{ number_format((float)$booking->total_price, 2) }}</td>
                        <td><span class="badge bg-secondary">{{ strtoupper($booking->status) }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('owner.booking.status', $booking) }}" class="d-flex gap-1">
                                @csrf
                                <select name="status" class="form-select form-select-sm">
                                    @foreach(['pending', 'confirmed', 'cancelled', 'completed', 'no_show'] as $status)
                                        <option value="{{ $status }}" @selected($booking->status === $status)>{{ strtoupper($status) }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-outline-dark">Save</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No bookings available.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $bookings->links() }}</div>
@endsection
