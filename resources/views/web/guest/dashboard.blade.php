@extends('web.layouts.app')

@section('title', 'Guest Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">My Bookings</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('home') }}" class="btn btn-outline-dark">Browse More Baths</a>
        <form method="POST" action="{{ route('guest.logout') }}">
            @csrf
            <button class="btn btn-dark">Logout</button>
        </form>
    </div>
</div>

<div class="card card-shadow rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>Booking ID</th>
                    <th>Bath</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Guests</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->booking_id }}</td>
                        <td>{{ optional($booking->bath)->name }}</td>
                        <td>{{ optional($booking->booking_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</td>
                        <td>{{ $booking->number_of_guests }}</td>
                        <td>Nu. {{ number_format((float) $booking->total_price, 2) }}</td>
                        <td><span class="badge bg-secondary">{{ strtoupper($booking->payment_status) }}</span></td>
                        <td><span class="badge bg-{{ in_array($booking->status, ['confirmed', 'completed']) ? 'success' : ($booking->status === 'cancelled' ? 'danger' : 'warning') }}">{{ strtoupper($booking->status) }}</span></td>
                        <td>
                            @if(!in_array($booking->status, ['cancelled', 'completed']))
                                <form method="POST" action="{{ route('guest.booking.cancel', $booking) }}" onsubmit="return confirm('Cancel this booking?')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger">Cancel</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No bookings yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $bookings->links() }}</div>
@endsection
