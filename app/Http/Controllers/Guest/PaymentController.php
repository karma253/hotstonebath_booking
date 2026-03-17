<?php

namespace App\Http\Controllers\Guest;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Show payment method selection page
     */
    public function showPaymentMethods($bookingId)
    {
        $booking = Booking::where('booking_id', $bookingId)->firstOrFail();
        
        // Only allow payment if booking is pending
        if ($booking->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This booking cannot be paid.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'booking' => [
                'id' => $booking->id,
                'booking_id' => $booking->booking_id,
                'total_price' => $booking->total_price,
                'guest_name' => $booking->guest_name,
                'bath_name' => $booking->bath->name ?? 'Bath Service',
                'booking_date' => $booking->booking_date,
                'start_time' => $booking->start_time,
            ],
            'payment_methods' => [
                [
                    'id' => 'cash',
                    'name' => 'Cash',
                    'icon' => '💵',
                    'description' => 'Pay at the bath facility'
                ],
                [
                    'id' => 'digital',
                    'name' => 'Digital Payment',
                    'icon' => '📱',
                    'description' => 'Pay using banking apps'
                ]
            ]
        ]);
    }

    /**
     * Show banking apps selection page for digital payment
     */
    public function showBankingApps($bookingId)
    {
        $booking = Booking::where('booking_id', $bookingId)->firstOrFail();

        return response()->json([
            'success' => true,
            'booking' => [
                'booking_id' => $booking->booking_id,
                'total_price' => (float) $booking->total_price,
                'guest_name' => $booking->guest_name,
            ],
            'banking_apps' => [
                [
                    'id' => 'MBoB',
                    'name' => 'Mobile Banking (MBoB)',
                    'logo' => '🏦',
                    'color' => '#003366',
                    'description' => 'Official Mobile Banking App'
                ],
                [
                    'id' => 'MPay',
                    'name' => 'MPay',
                    'logo' => '💳',
                    'color' => '#FF6B6B',
                    'description' => 'Digital Payment Solution'
                ],
                [
                    'id' => 'BDBL',
                    'name' => 'Bhutan Development Bank',
                    'logo' => '🏛️',
                    'color' => '#4CAF50',
                    'description' => 'BDBL Payment Gateway'
                ]
            ]
        ]);
    }

    /**
     * Process payment with PIN verification
     */
    public function processPayment(Request $request, $bookingId)
    {
        $request->validate([
            'banking_app' => 'required|in:MBoB,MPay,BDBL',
            'pin' => 'required|digits:4',
        ]);

        try {
            DB::beginTransaction();

            $booking = Booking::where('booking_id', $bookingId)->lockForUpdate()->firstOrFail();

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
                    'redirect_url' => '/booking/' . $booking->booking_id . '/confirmation'
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
     * Handle cash payment method selection
     */
    public function processCashPayment($bookingId)
    {
        try {
            DB::beginTransaction();

            $booking = Booking::where('booking_id', $bookingId)->lockForUpdate()->firstOrFail();

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
                'redirect_url' => '/booking/' . $booking->booking_id . '/confirmation'
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
     * Retry payment after failure
     */
    public function retryPayment(Request $request, $bookingId)
    {
        $request->validate([
            'banking_app' => 'required|in:MBoB,MPay,BDBL',
            'pin' => 'required|digits:4',
        ]);

        try {
            DB::beginTransaction();

            $booking = Booking::where('booking_id', $bookingId)->lockForUpdate()->firstOrFail();
            $previousTransaction = Transaction::where('booking_id', $booking->id)
                ->where('status', 'failed')
                ->latest()
                ->first();

            if (!$previousTransaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'No failed transaction to retry.'
                ], 400);
            }

            // Simulate payment processing
            sleep(rand(2, 3));

            $correctPin = '1234';
            $isPinCorrect = $request->pin === $correctPin;

            if ($isPinCorrect) {
                // Payment successful
                $previousTransaction->update([
                    'status' => 'success',
                    'error_message' => null,
                    'processed_at' => now(),
                ]);

                $booking->update([
                    'payment_status' => 'paid',
                    'payment_date' => now(),
                    'status' => 'confirmed',
                    'confirmed_at' => now(),
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Payment Successful!',
                    'transaction_id' => $previousTransaction->transaction_id,
                ]);
            } else {
                // Payment failed again
                $previousTransaction->increment('retry_count');

                DB::commit();

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid PIN. Please try again.',
                    'retry_count' => $previousTransaction->retry_count
                ], 400);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error during payment retry: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus($bookingId)
    {
        $booking = Booking::where('booking_id', $bookingId)->firstOrFail();
        $transaction = Transaction::where('booking_id', $booking->id)->latest()->first();

        return response()->json([
            'success' => true,
            'booking' => [
                'booking_id' => $booking->booking_id,
                'payment_status' => $booking->payment_status,
                'status' => $booking->status,
            ],
            'transaction' => $transaction ? [
                'transaction_id' => $transaction->transaction_id,
                'status' => $transaction->status,
                'payment_method' => $transaction->payment_method,
                'amount' => $transaction->amount,
                'processed_at' => $transaction->processed_at,
            ] : null,
        ]);
    }
}
