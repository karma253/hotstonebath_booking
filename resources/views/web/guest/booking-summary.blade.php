@extends('web.layouts.app')

@section('title', 'Booking Confirmation')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-shadow rounded-4">
            <div class="card-body p-4 p-lg-5">
                <h1 class="h3 text-success mb-3">Booking Confirmed</h1>
                <p class="text-muted mb-4">Your booking has been submitted successfully.</p>

                <div class="row g-3">
                    <div class="col-md-6"><strong>Booking ID:</strong> {{ $booking->booking_id }}</div>
                    <div class="col-md-6"><strong>Bath Name:</strong> {{ optional($booking->bath)->name }}</div>
                    <div class="col-md-6"><strong>Date:</strong> {{ optional($booking->booking_date)->format('d M Y') }}</div>
                    <div class="col-md-6"><strong>Time:</strong> {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</div>
                    <div class="col-md-6"><strong>Guests:</strong> {{ $booking->number_of_guests }}</div>
                    <div class="col-md-6"><strong>Total Price:</strong> Nu. {{ number_format((float) $booking->total_price, 2) }}</div>
                    <div class="col-md-6"><strong>Payment:</strong> {{ strtoupper($booking->payment_method) }}</div>
                    <div class="col-md-6"><strong>Status:</strong> {{ strtoupper($booking->status) }}</div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    @if($booking->status === 'pending' && $booking->payment_status === 'pending')
                        <a href="{{ route('guest.booking.payment', $booking) }}" class="btn btn-primary">Proceed to Payment</a>
                    @endif
                    <a href="{{ route('guest.dashboard') }}" class="btn btn-dark">View Booking History</a>
                    <a href="{{ route('home') }}" class="btn btn-outline-dark">Continue Browsing</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
