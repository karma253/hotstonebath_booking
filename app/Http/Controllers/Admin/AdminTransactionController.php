<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class AdminTransactionController extends Controller
{
    /**
     * Get recent transactions for the admin dashboard
     * 
     * @return JsonResponse
     */
    public function getRecentTransactions(): JsonResponse
    {
        $transactions = Transaction::query()
            ->with(['user', 'booking'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'transaction_id' => $transaction->transaction_id,
                    'user_name' => $transaction->user->name ?? 'Guest',
                    'booking_id' => $transaction->booking->booking_id ?? 'N/A',
                    'payment_method' => $transaction->payment_method,
                    'amount' => $transaction->amount,
                    'status' => $transaction->status,
                    'date' => $transaction->created_at->format('M d, Y H:i'),
                    'created_at' => $transaction->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $transactions,
            'count' => count($transactions),
        ]);
    }
}
