<?php

namespace App\Http\Controllers\Web;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     * Process digital payment
     */
    public function processDigitalPayment(Request $request, Booking $booking)
    {
        $user = Auth::user();
        
        // Verify user owns this booking
        if (!$user || $user->role !== 'guest' || $booking->guest_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'banking_app' => 'required|in:MBoB,MPay,BDBL',
            'pin' => 'required|digits:4',
        ]);

        try {
            DB::beginTransaction();

            // Check if booking is still pending
            if ($booking->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This booking cannot be paid.'
                ], 400);
            }

            // Generate transaction ID
            $transactionId = Transaction::generateTransactionId();

            // Simulate payment processing (2-3 second delay)
            sleep(rand(2, 3));

            // Validate PIN (correct PIN: 1234)
            $correctPin = '1234';
            $isPinCorrect = $request->pin === $correctPin;

            if ($isPinCorrect) {
                // Payment successful
                $transaction = Transaction::create([
                    'transaction_id' => $transactionId,
                    'user_id' => $booking->guest_id,
                    'booking_id' => $booking->id,
                    'payment_method' => $request->banking_app,
                    'amount' => $booking->total_price,
                    'status' => 'success',
                    'processed_at' => now(),
                ]);

                // Update booking status
                $booking->update([
                    'payment_status' => 'paid',
                    'payment_date' => now(),
                    'payment_method' => 'online',
                    'status' => 'confirmed',
                    'confirmed_at' => now(),
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Payment Successful!',
                    'transaction_id' => $transactionId,
                    'redirect_url' => route('guest.booking.confirmation', $booking) . '?transaction=' . $transactionId
                ]);
            } else {
                // Payment failed
                $transaction = Transaction::create([
                    'transaction_id' => $transactionId,
                    'user_id' => $booking->guest_id,
                    'booking_id' => $booking->id,
                    'payment_method' => $request->banking_app,
                    'amount' => $booking->total_price,
                    'status' => 'failed',
                    'error_message' => 'Invalid PIN entered',
                    'retry_count' => 1,
                    'processed_at' => now(),
                ]);

                DB::commit();

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid PIN. Please try again.',
                    'transaction_id' => $transactionId,
                    'retry_allowed' => true
                ], 400);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Payment processing error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process cash payment
     */
    public function processCashPayment(Booking $booking)
    {
        $user = Auth::user();
        
        // Verify user owns this booking
        if (!$user || $user->role !== 'guest' || $booking->guest_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            DB::beginTransaction();

            if ($booking->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This booking cannot be paid.'
                ], 400);
            }

            // Create transaction record for cash
            $transactionId = Transaction::generateTransactionId();
            
            Transaction::create([
                'transaction_id' => $transactionId,
                'user_id' => $booking->guest_id,
                'booking_id' => $booking->id,
                'payment_method' => 'cash',
                'amount' => $booking->total_price,
                'status' => 'pending',
            ]);

            // Update booking - mark as confirmed with pending payment
            $booking->update([
                'payment_method' => 'on_site',
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking confirmed! Please pay when you arrive.',
                'transaction_id' => $transactionId,
                'redirect_url' => route('guest.booking.confirmation', $booking) . '?method=cash'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error processing cash payment: ' . $e->getMessage()
            ], 500);
        }
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
