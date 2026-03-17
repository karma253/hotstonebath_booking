<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Bath;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ProviderBookingController extends Controller
{
    /**
     * Get all bookings for provider's bath.
     */
    public function getAllBookings(Request $request)
    {
        $bath = Bath::where('owner_id', $request->user()->id)->firstOrFail();

        $bookings = Booking::where('bath_id', $bath->id)
            ->with(['guest', 'service'])
            ->orderBy('booking_date', 'desc')
            ->paginate(20);

        return response()->json($bookings);
    }

    /**
     * Get pending bookings (awaiting confirmation).
     */
    public function getPendingBookings(Request $request)
    {
        $bath = Bath::where('owner_id', $request->user()->id)->firstOrFail();

        $bookings = Booking::where('bath_id', $bath->id)
            ->where('status', 'pending')
            ->with(['guest', 'service'])
            ->orderBy('booking_date', 'asc')
            ->get();

        return response()->json($bookings);
    }

    /**
     * Confirm a booking.
     */
    public function confirmBooking(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->bath->owner_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($booking->status !== 'pending') {
            return response()->json(['error' => 'Booking cannot be confirmed in its current status'], 400);
        }

        $booking->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        // Create transaction if booking is paid and no transaction exists yet
        if ($booking->payment_status === 'paid') {
            $existingTransaction = Transaction::where('booking_id', $booking->id)->first();
            
            if (!$existingTransaction) {
                // Determine payment method from booking
                $paymentMethod = 'unknown';
                if ($booking->special_requests && str_contains($booking->special_requests, 'Banking App:')) {
                    // Extract banking app from special requests
                    if (str_contains($booking->special_requests, 'MBoB')) {
                        $paymentMethod = 'MBoB';
                    } elseif (str_contains($booking->special_requests, 'MPay')) {
                        $paymentMethod = 'MPay';
                    } elseif (str_contains($booking->special_requests, 'BDBL')) {
                        $paymentMethod = 'BDBL';
                    }
                }

                Transaction::create([
                    'transaction_id' => 'TXN' . now()->format('YmdHis') . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT),
                    'user_id' => $booking->guest_id,
                    'booking_id' => $booking->id,
                    'payment_method' => $paymentMethod,
                    'amount' => $booking->total_price,
                    'status' => 'success',
                    'processed_at' => now(),
                ]);
            }
        }

        return response()->json([
            'message' => 'Booking confirmed successfully',
            'booking' => $booking,
        ]);
    }

    /**
     * Reject a booking.
     */
    public function rejectBooking(Request $request, $bookingId)
    {
        $validated = $request->validate([
            'cancellation_reason' => 'nullable|string',
        ]);

        $booking = Booking::findOrFail($bookingId);

        if ($booking->bath->owner_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($booking->status !== 'pending') {
            return response()->json(['error' => 'Booking cannot be rejected in its current status'], 400);
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason'] ?? 'Rejected by provider',
            'cancelled_at' => now(),
        ]);

        return response()->json([
            'message' => 'Booking rejected successfully',
            'booking' => $booking,
        ]);
    }

    /**
     * Mark booking as completed.
     */
    public function completeBooking(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->bath->owner_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($booking->status !== 'confirmed') {
            return response()->json(['error' => 'Only confirmed bookings can be marked as completed'], 400);
        }

        $booking->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Booking marked as completed',
            'booking' => $booking,
        ]);
    }

    /**
     * Mark booking as no-show.
     */
    public function markNoShow(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->bath->owner_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($booking->status !== 'confirmed') {
            return response()->json(['error' => 'Only confirmed bookings can be marked as no-show'], 400);
        }

        $booking->update([
            'status' => 'no_show',
        ]);

        return response()->json([
            'message' => 'Booking marked as no-show',
            'booking' => $booking,
        ]);
    }

    /**
     * Get booking reports.
     */
    public function getReports(Request $request)
    {
        $bath = Bath::where('owner_id', $request->user()->id)->firstOrFail();

        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $dailyBookings = Booking::where('bath_id', $bath->id)
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->selectRaw('DATE(booking_date) as date, COUNT(*) as count, SUM(total_price) as revenue')
            ->groupBy('date')
            ->get();

        $statusSummary = Booking::where('bath_id', $bath->id)
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return response()->json([
            'daily_bookings' => $dailyBookings,
            'status_summary' => $statusSummary,
        ]);
    }
}
