<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Console\Command;

class CreateTransactionsForBookings extends Command
{
    protected $signature = 'transactions:create-for-bookings';
    protected $description = 'Create transactions for the 2 test bookings';

    public function handle()
    {
        $bookingIds = [
            'BOOKING-20260317-98635',
            'BOOKING-20260317-06928',
        ];

        $validPaymentMethods = ['MBoB', 'MPay', 'BDBL', 'cash'];

        foreach ($bookingIds as $bookingId) {
            $booking = Booking::where('booking_id', $bookingId)->first();

            if (!$booking) {
                $this->error("Booking {$bookingId} not found");
                continue;
            }

            // Check if transaction already exists for this booking
            $existingTransaction = Transaction::where('booking_id', $booking->id)->first();
            if ($existingTransaction) {
                $this->warn("Transaction already exists for {$bookingId}");
                continue;
            }

            // Determine valid payment method
            $paymentMethod = $booking->payment_method ?? 'cash';
            if (!in_array($paymentMethod, $validPaymentMethods)) {
                $paymentMethod = 'cash'; // Default to cash if invalid
            }

            // Create transaction
            $transaction = Transaction::create([
                'transaction_id' => Transaction::generateTransactionId(),
                'user_id' => $booking->guest_id,
                'booking_id' => $booking->id,
                'payment_method' => $paymentMethod,
                'amount' => $booking->total_price,
                'status' => 'success',
                'error_message' => null,
                'retry_count' => 0,
                'processed_at' => $booking->payment_date ?? now(),
            ]);

            $this->info("Created transaction {$transaction->transaction_id} for booking {$bookingId}");
        }

        $this->info('Done!');
    }
}
