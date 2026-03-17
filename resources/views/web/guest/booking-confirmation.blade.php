@extends('web.layouts.app')

@section('title', 'Booking Confirmation - ' . $booking->booking_id)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Success Card -->
        @if($transaction && $transaction->status === 'success')
        <div class="card card-shadow rounded-4 border-success">
            <div class="card-body p-4 p-lg-5 text-center">
                <div style="font-size: 64px; color: #28a745; margin-bottom: 20px;">✓</div>
                <h1 class="h3 text-success mb-2">Payment Successful!</h1>
                <p class="text-muted mb-4">Your booking has been confirmed and paid.</p>

                <!-- Transaction Details -->
                <div class="alert alert-success mb-4" style="background-color: #f0f7f4; border-color: #28a745;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Transaction ID</small>
                            <strong class="d-block" style="color: #28a745; font-size: 16px;">{{ $transaction->transaction_id }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Payment Method</small>
                            <strong class="d-block">{{ ucfirst($transaction->payment_method) }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Amount Paid</small>
                            <strong class="d-block">Nu. {{ number_format((float) $transaction->amount, 2) }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Payment Status</small>
                            <strong class="d-block"><span class="badge bg-success">{{ ucfirst($transaction->status) }}</span></strong>
                        </div>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="border-top pt-4">
                    <h5 class="mb-3 text-start">Booking Details</h5>
                    <div class="row g-3 text-start">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Booking ID</small>
                            <strong>{{ $booking->booking_id }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Bath</small>
                            <strong>{{ optional($booking->bath)->name }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Date & Time</small>
                            <strong>{{ optional($booking->booking_date)->format('d M Y') }} | {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Guests</small>
                            <strong>{{ $booking->number_of_guests }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Service</small>
                            <strong>{{ optional($booking->service)->service_type }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Location</small>
                            <strong>{{ optional($booking->bath->dzongkhag)->name }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @elseif($booking->payment_method === 'on_site' && $booking->status === 'confirmed')
        <!-- Cash Payment Confirmation -->
        <div class="card card-shadow rounded-4 border-warning">
            <div class="card-body p-4 p-lg-5 text-center">
                <div style="font-size: 64px; margin-bottom: 20px;">💵</div>
                <h1 class="h3 text-warning mb-2">Booking Confirmed!</h1>
                <p class="text-muted mb-4">Your booking is confirmed. Please bring exact cash amount when you arrive.</p>

                <!-- Payment Notice -->
                <div class="alert alert-warning mb-4" style="background-color: #fffbea; border-color: #ffc107;">
                    <div class="row g-3">
                        <div class="col-12">
                            <h5 class="mb-3">💸 Payment Due on Arrival</h5>
                            <div style="font-size: 28px; color: #ff9800; font-weight: bold; margin-bottom: 10px;">Nu. {{ number_format((float) $booking->total_price, 2) }}</div>
                            <p class="text-muted mb-0"><strong>Payment Method:</strong> Cash (at bath facility)</p>
                        </div>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="border-top pt-4">
                    <h5 class="mb-3 text-start">Booking Details</h5>
                    <div class="row g-3 text-start">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Booking ID</small>
                            <strong>{{ $booking->booking_id }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Bath</small>
                            <strong>{{ optional($booking->bath)->name }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Date & Time</small>
                            <strong>{{ optional($booking->booking_date)->format('d M Y') }} | {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Guests</small>
                            <strong>{{ $booking->number_of_guests }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Service</small>
                            <strong>{{ optional($booking->service)->service_type }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Location</small>
                            <strong>{{ optional($booking->bath->dzongkhag)->name }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Payment Pending / Failed -->
        <div class="card card-shadow rounded-4 border-danger">
            <div class="card-body p-4 p-lg-5 text-center">
                <div style="font-size: 64px; color: #dc3545; margin-bottom: 20px;">⚠️</div>
                <h1 class="h3 text-danger mb-2">Payment Issue</h1>
                <p class="text-muted mb-4">Your payment could not be completed. Please try again.</p>

                <div class="alert alert-danger mb-4">
                    <p class="mb-0"><strong>Booking Status:</strong> {{ ucfirst($booking->status) }}</p>
                    <p class="mb-0"><strong>Amount Due:</strong> Nu. {{ number_format((float) $booking->total_price, 2) }}</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('guest.booking.payment', $booking) }}" class="btn btn-primary flex-grow-1">Try Payment Again</a>
                    <a href="{{ route('guest.booking.summary', $booking) }}" class="btn btn-outline-secondary flex-grow-1">View Booking</a>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-4 d-flex gap-2">
            <a href="{{ route('guest.dashboard') }}" class="btn btn-outline-dark flex-grow-1">View All Bookings</a>
            <a href="{{ route('home') }}" class="btn btn-dark flex-grow-1">Continue Browsing</a>
        </div>
    </div>
</div>

<style>
.border-success {
    border-color: #28a745 !important;
}

.border-warning {
    border-color: #ffc107 !important;
}

.border-danger {
    border-color: #dc3545 !important;
}

.card-shadow {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.rounded-4 {
    border-radius: 1rem;
}
</style>

<script>
// Auto-refresh to check payment status every 3 seconds (for pending payments)
@if(!$transaction || $transaction->status === 'pending')
setTimeout(() => {
    location.reload();
}, 3000);
@endif
</script>
@endsection
