<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bath;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminPortalController extends Controller
{
    public function showLogin(): View
    {
        return view('web.auth.admin-login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Convenience bootstrap for first admin account.
        if ($validated['email'] === 'admin@example.com' && $validated['password'] === 'password') {
            User::query()->firstOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'System Admin',
                    'phone' => '17111111',
                    'address' => 'Thimphu',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                    'status' => 'active',
                    'approved_at' => now(),
                ]
            );
        }

        if (! Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            return back()->withInput()->with('error', 'Invalid email or password.');
        }

        $user = Auth::user();
        if (! $user || $user->role !== 'admin') {
            Auth::logout();
            return back()->withInput()->with('error', 'This login is for admin accounts only.');
        }

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function dashboard(): View|RedirectResponse
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $pendingOwners = User::query()
            ->with('baths')
            ->where('role', 'owner')
            ->where('status', 'pending_verification')
            ->latest()
            ->get();

        $pendingListings = Bath::query()
            ->with(['owner', 'dzongkhag'])
            ->where('status', 'pending_verification')
            ->latest()
            ->get();

        $stats = [
            'customers' => User::query()->where('role', 'guest')->count(),
            'owners' => User::query()->whereIn('role', ['owner', 'manager'])->count(),
            'bookings' => Booking::query()->count(),
            'active_listings' => Bath::query()->where('status', 'active')->count(),
        ];

        // Fetch real transactions from database
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

        return view('web.admin.dashboard', compact('pendingOwners', 'pendingListings', 'stats', 'transactions'));
    }

    public function approveOwner(User $owner): RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        if ($owner->role !== 'owner') {
            return back()->with('error', 'Selected user is not an owner account.');
        }

        $owner->update([
            'status' => 'approved',
            'approved_at' => now(),
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Owner approved successfully.');
    }

    public function rejectOwner(Request $request, User $owner): RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        if ($owner->role !== 'owner') {
            return back()->with('error', 'Selected user is not an owner account.');
        }

        $owner->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'rejection_reason' => $validated['reason'],
        ]);

        return back()->with('success', 'Owner rejected successfully.');
    }

    public function updateListingStatus(Request $request, Bath $bath): RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['active', 'suspended'])],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $bath->update([
            'status' => $validated['status'],
            'verified_at' => $validated['status'] === 'active' ? now() : null,
            'verification_notes' => $validated['notes'] ?? null,
        ]);

        if ($validated['status'] === 'active') {
            $bath->owner?->update([
                'status' => 'active',
                'approved_at' => $bath->owner?->approved_at ?: now(),
            ]);
        }

        return back()->with('success', 'Listing status updated successfully.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Get recent transactions as JSON for dashboard AJAX calls
     */
    public function getRecentTransactionsJson()
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
                    'created_at' => $transaction->created_at->toIso8601String(),
                ];
            })
            ->toArray();

        return response()->json([
            'success' => true,
            'data' => $transactions,
            'count' => count($transactions),
        ]);
    }
}
