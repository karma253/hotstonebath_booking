<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bath;
use App\Models\Transaction;
use App\Models\VerificationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminVerificationController extends Controller
{
    /**
     * Get all pending owner registrations.
     */
    public function getPendingOwners()
    {
        $owners = User::where('status', 'pending_verification')
            ->where('role', '!=', 'guest')
            ->with(['baths' => function ($query) {
                $query->with(['documents', 'images']);
            }])
            ->get();

        return response()->json([
            'pending_count' => $owners->count(),
            'owners' => $owners,
        ]);
    }

    /**
     * Get owner details for verification.
     */
    public function getOwnerDetails($userId)
    {
        $user = User::with(['baths' => function ($query) {
            $query->with(['documents', 'facilities', 'services', 'availabilities']);
        }])->findOrFail($userId);

        if (!$user->isOwner()) {
            return response()->json(['error' => 'User is not an owner'], 403);
        }

        return response()->json($user);
    }

    /**
     * Approve owner registration.
     */
    public function approveOwner(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        if ($user->status !== 'pending_verification') {
            return response()->json(['error' => 'User status does not allow approval'], 400);
        }

        $user->update([
            'status' => 'active',
            'approved_at' => now(),
            'reviewed_at' => now(),
        ]);

        // Approve associated bath
        $bath = Bath::where('owner_id', $userId)->first();
        if ($bath) {
            $bath->update([
                'status' => 'active',
                'verified_at' => now(),
            ]);

            // Approve all verification documents
            VerificationDocument::where('bath_id', $bath->id)
                ->update(['verification_status' => 'verified', 'verified_at' => now()]);
        }

        return response()->json([
            'message' => 'Owner approved successfully',
            'user' => $user,
        ]);
    }

    /**
     * Reject owner registration.
     */
    public function rejectOwner(Request $request, $userId)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $user = User::findOrFail($userId);

        if ($user->status !== 'pending_verification') {
            return response()->json(['error' => 'User status does not allow rejection'], 400);
        }

        $user->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'reviewed_at' => now(),
        ]);

        // Reject associated bath
        $bath = Bath::where('owner_id', $userId)->first();
        if ($bath) {
            $bath->update([
                'status' => 'suspended',
                'verification_notes' => $validated['rejection_reason'],
            ]);
        }

        return response()->json([
            'message' => 'Owner registration rejected',
            'user' => $user,
        ]);
    }

    /**
     * Verify individual documents.
     */
    public function verifyDocument(Request $request, $documentId)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:verified,rejected',
            'verification_notes' => 'nullable|string',
        ]);

        $document = VerificationDocument::findOrFail($documentId);

        $document->update([
            'verification_status' => $validated['verification_status'],
            'verification_notes' => $validated['verification_notes'],
            'verified_at' => now(),
        ]);

        return response()->json([
            'message' => 'Document verification updated',
            'document' => $document,
        ]);
    }

    /**
     * Get dashboard statistics.
     */
    public function getDashboardStats()
    {
        $pendingCount = User::where('status', 'pending_verification')
            ->where('role', '!=', 'guest')
            ->count();

        $approvedCount = User::where('status', 'active')
            ->where('role', '!=', 'guest')
            ->count();

        $activeBaths = Bath::where('status', 'active')->count();
        $pendingBaths = Bath::where('status', 'pending_verification')->count();

        return response()->json([
            'pending_owners' => $pendingCount,
            'approved_owners' => $approvedCount,
            'active_baths' => $activeBaths,
            'pending_baths' => $pendingBaths,
        ]);
    }

    /**
     * Get recent transactions for dashboard
     */
    public function getRecentTransactions($limit = 10)
    {
        $transactions = Transaction::with(['user', 'booking'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        $transactionData = $transactions->map(function ($transaction) {
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
            'total' => $transactionData->count(),
            'transactions' => $transactionData,
        ]);
    }

    /**
     * Get transaction statistics
     */
    public function getTransactionStats()
    {
        $totalTransactions = Transaction::count();
        $successfulTransactions = Transaction::where('status', 'success')->count();
        $failedTransactions = Transaction::where('status', 'failed')->count();
        $totalAmount = Transaction::where('status', 'success')->sum('amount');

        $transactionsByMethod = Transaction::selectRaw('payment_method, COUNT(*) as count, SUM(CASE WHEN status = "success" THEN 1 ELSE 0 END) as successful')
            ->groupBy('payment_method')
            ->get();

        return response()->json([
            'success' => true,
            'total_transactions' => $totalTransactions,
            'successful_transactions' => $successfulTransactions,
            'failed_transactions' => $failedTransactions,
            'total_amount' => $totalAmount,
            'by_method' => $transactionsByMethod,
        ]);
    }

    /**
     * Filter transactions
     */
    public function filterTransactions(Request $request)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:success,failed,pending',
            'payment_method' => 'nullable|in:MBoB,MPay,BDBL,cash',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'limit' => 'nullable|integer|max:100',
        ]);

        $query = Transaction::with(['user', 'booking']);

        if ($validated['status'] ?? null) {
            $query->where('status', $validated['status']);
        }

        if ($validated['payment_method'] ?? null) {
            $query->where('payment_method', $validated['payment_method']);
        }

        if ($validated['date_from'] ?? null) {
            $query->whereDate('created_at', '>=', $validated['date_from']);
        }

        if ($validated['date_to'] ?? null) {
            $query->whereDate('created_at', '<=', $validated['date_to']);
        }

        $limit = $validated['limit'] ?? 10;
        $transactions = $query->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        $transactionData = $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'transaction_id' => $transaction->transaction_id,
                'user_name' => $transaction->user->name ?? 'Guest',
                'booking_id' => $transaction->booking->booking_id ?? 'N/A',
                'payment_method' => $transaction->payment_method,
                'amount' => $transaction->amount,
                'status' => $transaction->status,
                'date' => $transaction->created_at->format('M d, Y H:i'),
            ];
        });

        return response()->json([
            'success' => true,
            'total' => $transactionData->count(),
            'transactions' => $transactionData,
        ]);
    }
}
