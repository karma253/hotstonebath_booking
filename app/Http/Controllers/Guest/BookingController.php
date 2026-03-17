<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Bath;
use App\Models\Booking;
use App\Models\BathService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Create a new booking.
     */
    public function createBooking(Request $request)
    {
        $validated = $request->validate([
            'bath_id' => 'required|exists:baths,id',
            'service_id' => 'required|exists:bath_services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'number_of_guests' => 'required|integer|min:1',
            'payment_method' => 'required|in:online,on_site',
            'special_requests' => 'nullable|string',
        ]);

        $bath = Bath::findOrFail($validated['bath_id']);
        $service = BathService::findOrFail($validated['service_id']);

        // Check if service belongs to bath
        if ($service->bath_id !== $bath->id) {
            return response()->json(['error' => 'Service does not belong to this bath'], 400);
        }

        // Check if bath is active
        if ($bath->status !== 'active') {
            return response()->json(['error' => 'Bath is not available for booking'], 400);
        }

        // Calculate end time
        $startTime = \Carbon\Carbon::createFromFormat(
            'H:i',
            $validated['start_time'],
            'UTC'
        );
        $endTime = $startTime->copy()->addMinutes($service->duration_minutes);

        // Check availability
        $conflict = Booking::where('bath_id', $bath->id)
            ->where('booking_date', $validated['booking_date'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime->format('H:i'), $endTime->format('H:i')])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $endTime->format('H:i'))
                            ->where('end_time', '>', $startTime->format('H:i'));
                    });
            })
            ->exists();

        if ($conflict) {
            return response()->json(['error' => 'Time slot is not available'], 400);
        }

        // Check guest limit
        if ($validated['number_of_guests'] > $service->max_guests) {
            return response()->json([
                'error' => "Maximum {$service->max_guests} guests allowed for this service"
            ], 400);
        }

        // Generate unique booking ID
        $bookingId = 'BOOKING-' . date('Ymd') . '-' . str_pad(
            Booking::whereDate('created_at', today())->count() + 1,
            5,
            '0',
            STR_PAD_LEFT
        );

        $user = $request->user();
        $totalPrice = $service->price;

        // Create booking
        $booking = Booking::create([
            'booking_id' => $bookingId,
            'guest_id' => $user->id,
            'bath_id' => $bath->id,
            'service_id' => $service->id,
            'guest_name' => $user->name,
            'guest_email' => $user->email,
            'guest_phone' => $user->phone,
            'booking_date' => $validated['booking_date'],
            'start_time' => $startTime->format('H:i'),
            'end_time' => $endTime->format('H:i'),
            'number_of_guests' => $validated['number_of_guests'],
            'total_price' => $totalPrice,
            'payment_method' => $validated['payment_method'],
            'payment_status' => $validated['payment_method'] === 'online' ? 'pending' : 'pending',
            'status' => $bath->booking_type === 'instant' ? 'confirmed' : 'pending',
            'confirmed_at' => $bath->booking_type === 'instant' ? now() : null,
            'special_requests' => $validated['special_requests'],
        ]);

        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $booking,
            'action_required' => $validated['payment_method'] === 'online' ? 'payment' : 'confirmation',
        ], 201);
    }

    /**
     * Process payment for booking.
     */
    public function processPayment(Request $request, $bookingId)
    {
        $validated = $request->validate([
            'payment_details' => 'required|array', // In real scenario, this would contain card details
        ]);

        $booking = Booking::findOrFail($bookingId);

        if ($booking->guest_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($booking->payment_status !== 'pending') {
            return response()->json(['error' => 'Payment already processed'], 400);
        }

        // Here you would integrate with payment gateway (Stripe, PayPal, etc.)
        // For now, we'll simulate successful payment
        $booking->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);

        return response()->json([
            'message' => 'Payment processed successfully',
            'booking' => $booking,
        ]);
    }

    /**
     * Get guest's bookings.
     */
    public function getMyBookings(Request $request)
    {
        $bookings = Booking::where('guest_id', $request->user()->id)
            ->with(['bath', 'service', 'review'])
            ->orderBy('booking_date', 'desc')
            ->paginate(20);

        return response()->json($bookings);
    }

    /**
     * Get booking details.
     */
    public function getBookingDetails(Request $request, $bookingId)
    {
        $booking = Booking::with(['bath', 'service', 'review'])
            ->findOrFail($bookingId);

        if ($booking->guest_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($booking);
    }

    /**
     * Cancel booking.
     */
    public function cancelBooking(Request $request, $bookingId)
    {
        $validated = $request->validate([
            'cancellation_reason' => 'nullable|string',
        ]);

        $booking = Booking::findOrFail($bookingId);

        if ($booking->guest_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json(['error' => 'Booking cannot be cancelled in its current status'], 400);
        }

        // Check if booking is not in the past
        if (\Carbon\Carbon::parse($booking->booking_date)->isPast()) {
            return response()->json(['error' => 'Cannot cancel past bookings'], 400);
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason'] ?? 'Cancelled by guest',
            'cancelled_at' => now(),
        ]);

        // Process refund if payment was made
        if ($booking->payment_status === 'paid') {
            $booking->update(['payment_status' => 'refunded']);
        }

        return response()->json([
            'message' => 'Booking cancelled successfully',
            'booking' => $booking,
        ]);
    }
}
