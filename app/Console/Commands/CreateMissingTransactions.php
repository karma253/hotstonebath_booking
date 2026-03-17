<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Console\Command;

class CreateMissingTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:create-missing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create transactions for all paid bookings that do not have a transaction yet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Finding paid bookings without transactions...');

        // Find all bookings that have payment_status = 'paid'
        $paidBookings = Booking::query()
            ->where('payment_status', 'paid')
            ->get();

        $createdCount = 0;
        foreach ($paidBookings as $booking) {
            // Check if transaction already exists for this booking
            $existingTransaction = Transaction::where('booking_id', $booking->id)->first();
            
            if ($existingTransaction) {
                $this->info("Transaction already exists for booking {$booking->booking_id}");
                continue;
            }

            // Determine payment method from special requests
            $paymentMethod = 'digital';
            if ($booking->special_requests && str_contains($booking->special_requests, 'Banking App:')) {
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

            $createdCount++;
            $this->info("Created transaction for booking {$booking->booking_id}");
        }

        $this->info("Successfully created {$createdCount} missing transactions.");
    }
}
