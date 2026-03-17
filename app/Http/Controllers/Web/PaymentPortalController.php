<?php

namespace App\Http\Controllers\Web;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentPortalController extends Controller
{
    /**
     * Show payment page for a booking
     */
    public function showPayment(Booking $booking): View|RedirectResponse
    {
        $user = Auth::user();
        
        // Verify user owns this booking
        if (!$user || $user->role !== 'guest' || $booking->guest_id !== $user->id) {
            return redirect()->route('guest.login');
        }

        // Only pending bookings can be paid
        if ($booking->status !== 'pending') {
            return redirect()->route('guest.booking.summary', $booking);
        }

        $booking->load(['bath.dzongkhag', 'service']);

        return view('web.guest.payment', compact('booking'));
    }

    /**
     * Show booking confirmation page
     */
    public function showConfirmation(Booking $booking): View|RedirectResponse
    {
        $user = Auth::user();
        
        // Verify user owns this booking
        if (!$user || $user->role !== 'guest' || $booking->guest_id !== $user->id) {
            return redirect()->route('guest.login');
        }

        $booking->load(['bath.dzongkhag', 'service']);
        $transaction = Transaction::where('booking_id', $booking->id)->latest()->first();

        return view('web.guest.booking-confirmation', compact('booking', 'transaction'));
    }
}
