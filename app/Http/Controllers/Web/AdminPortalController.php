<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bath;
use App\Models\BathService;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

        // Calculate key statistics
        $totalOwners = User::query()->where('role', 'owner')->count();
        $totalBookings = Booking::query()->count();
        $totalListings = Bath::query()->count();

        // Calculate revenue and commission
        $totalRevenue = Transaction::query()->sum('amount') ?? 0;
        $totalCommission = $totalRevenue * 0.15; // 15% commission
        
        // Calculate current month commission
        $monthlyRevenue = Transaction::query()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('amount') ?? 0;
        $monthlyCommission = $monthlyRevenue * 0.15;

        // Get top owners by revenue
        $topOwners = User::query()
            ->where('role', 'owner')
            ->with(['baths' => function ($query) {
                $query->with('bookings');
            }])
            ->get()
            ->map(function ($owner) {
                $revenue = Transaction::query()
                    ->whereHas('booking', function ($q) use ($owner) {
                        $q->whereHas('bath', function ($q2) use ($owner) {
                            $q2->where('owner_id', $owner->id);
                        });
                    })
                    ->sum('amount') ?? 0;
                
                return [
                    'id' => $owner->id,
                    'name' => $owner->name,
                    'revenue' => $revenue,
                    'bookings_count' => Booking::query()
                        ->whereHas('bath', function ($q) use ($owner) {
                            $q->where('owner_id', $owner->id);
                        })
                        ->count(),
                ];
            })
            ->sortByDesc('revenue')
            ->take(5)
            ->values();

        // Get recent bookings
        $recentBookings = Booking::query()
            ->with(['guest', 'bath'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($booking) {
                // Get the transaction for this booking to get amount
                $transaction = Transaction::query()
                    ->where('booking_id', $booking->id)
                    ->first();
                
                return [
                    'id' => $booking->id,
                    'user' => $booking->guest,
                    'bath' => $booking->bath,
                    'amount' => $transaction->amount ?? 0,
                    'status' => $transaction->status ?? 'pending',
                    'created_at' => $booking->created_at,
                ];
            });

        // Legacy data for old views
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

        $pendingServices = BathService::query()
            ->with(['bath.owner', 'bath.dzongkhag'])
            ->pendingApproval()
            ->latest()
            ->get();

        $stats = [
            'customers' => User::query()->where('role', 'guest')->count(),
            'owners' => $totalOwners,
            'bookings' => $totalBookings,
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

        return view('web.admin.dashboard', compact(
            'pendingOwners',
            'pendingListings',
            'pendingServices',
            'stats',
            'transactions',
            'totalRevenue',
            'totalCommission',
            'monthlyCommission',
            'totalOwners',
            'totalBookings',
            'totalListings',
            'topOwners',
            'recentBookings'
        ));
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

    public function approveService(BathService $service): RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $service->update([
            'approval_status' => 'approved',
            'approval_notes' => null,
            'reviewed_at' => now(),
            'is_available' => true,
        ]);

        // Ensure the linked bath and owner are visible after service approval.
        $bath = $service->bath;
        if ($bath) {
            $bath->update([
                'status' => 'active',
                'verified_at' => $bath->verified_at ?: now(),
            ]);

            $bath->owner?->update([
                'status' => 'active',
                'approved_at' => $bath->owner?->approved_at ?: now(),
                'reviewed_at' => now(),
                'rejection_reason' => null,
            ]);
        }

        return back()->with('success', 'Service approved successfully.');
    }

    public function rejectService(Request $request, BathService $service): RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $service->update([
            'approval_status' => 'rejected',
            'approval_notes' => $validated['reason'] ?? null,
            'reviewed_at' => now(),
            'is_available' => false,
        ]);

        return back()->with('success', 'Service rejected successfully.');
    }

    /**
     * Show users management page with all users and their approval status
     */
    public function showUsers(Request $request): View|RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->with('baths')->latest()->paginate(20);

        return view('web.admin.users', compact('users'));
    }

    /**
     * Show bookings management page with filters
     */
    public function showBookings(Request $request): View|RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $query = Booking::query()
            ->with(['guest', 'bath.owner', 'bath.dzongkhag']);

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('booking_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('booking_date', '<=', $request->input('end_date'));
        }

        // Filter by owner
        if ($request->filled('owner_id')) {
            $query->whereHas('bath', function ($q) {
                $q->where('owner_id', request()->input('owner_id'));
            });
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        // Filter by booking status
        if ($request->filled('booking_status')) {
            $query->where('status', $request->input('booking_status'));
        }

        $bookings = $query->latest()->paginate(20)->appends($request->query());
        $owners = User::where('role', 'owner')->orderBy('name')->get();

        return view('web.admin.bookings', compact('bookings', 'owners'));
    }

    /**
     * Show revenue and commission dashboard
     */
    public function showRevenue(Request $request): View|RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $commissionRate = 0.10; // 10% commission

        // Get owner revenue data
        $owners = User::where('role', 'owner')
            ->with(['baths.bookings'])
            ->orderBy('name')
            ->get();

        // Calculate revenue for each owner
        $ownerRevenue = $owners->map(function ($owner) use ($commissionRate) {
            $totalRevenue = $owner->baths->flatMap->bookings->sum('total_price');
            $commission = $totalRevenue * $commissionRate;
            $netEarnings = $totalRevenue - $commission;
            $bookingCount = $owner->baths->flatMap->bookings->count();

            return [
                'owner' => $owner,
                'total_bookings' => $bookingCount,
                'total_revenue' => $totalRevenue,
                'commission' => $commission,
                'net_earnings' => $netEarnings,
            ];
        });

        // Overall stats
        $stats = [
            'total_revenue' => Booking::sum('total_price'),
            'total_commission' => Booking::sum('total_price') * $commissionRate,
            'total_owner_earnings' => Booking::sum('total_price') * (1 - $commissionRate),
        ];

        return view('web.admin.revenue', compact('ownerRevenue', 'stats', 'commissionRate'));
    }

    /**
     * Approve a user
     */
    public function approveUser(User $user): RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $user->update([
            'status' => 'active',
            'approved_at' => now(),
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);

        // Send approval email to user
        try {
            Mail::to($user->email)->send(new \App\Mail\UserApprovedMail($user));
        } catch (\Exception $e) {
            \Log::error('Failed to send user approval email: ' . $e->getMessage());
        }

        return back()->with('success', "✅ {$user->name}'s account has been approved! Email sent to {$user->email}");
    }

    /**
     * Reject a user
     */
    public function rejectUser(Request $request, User $user): RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $user->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'rejection_reason' => $validated['reason'],
        ]);

        // Send rejection email to user
        try {
            Mail::to($user->email)->send(new \App\Mail\UserRejectedMail($user, $validated['reason']));
        } catch (\Exception $e) {
            \Log::error('Failed to send user rejection email: ' . $e->getMessage());
        }

        return back()->with('success', "❌ {$user->name}'s account has been rejected. Email sent to {$user->email}");
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

    // ===========================================
    // BATH MANAGEMENT SECTION
    // ===========================================

    /**
     * Manage all baths - list with filters
     */
    public function manageBaths(Request $request): View|RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $query = Bath::query()->with(['owner', 'dzongkhag', 'services']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by bath type
        if ($request->filled('type')) {
            $query->where('bath_type', $request->input('type'));
        }

        // Filter by owner
        if ($request->filled('owner_id')) {
            $query->where('owner_id', $request->input('owner_id'));
        }

        // Filter by location (dzongkhag)
        if ($request->filled('dzongkhag_id')) {
            $query->where('dzongkhag_id', $request->input('dzongkhag_id'));
        }

        // Search by name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $baths = $query->latest()->paginate(15);
        $owners = User::where('role', 'owner')->orderBy('name')->get();
        $dzongkhags = \App\Models\Dzongkhag::orderBy('name')->get();
        $bathTypes = ['menchu', 'dotsho', 'tshachu'];

        return view('web.admin.bath-management', compact('baths', 'owners', 'dzongkhags', 'bathTypes'));
    }

    /**
     * Show edit form for a bath
     */
    public function editBath(Bath $bath): View|RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $owners = User::where('role', 'owner')->orderBy('name')->get();
        $dzongkhags = \App\Models\Dzongkhag::orderBy('name')->get();
        $facilities = \App\Models\BathFacility::all();
        $bathTypes = ['menchu', 'dotsho', 'tshachu'];

        return view('web.admin.edit-bath', compact('bath', 'owners', 'dzongkhags', 'facilities', 'bathTypes'));
    }

    /**
     * Update bath details
     */
    public function updateBath(Request $request, Bath $bath): RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'bath_type' => ['required', Rule::in(['menchu', 'dotsho', 'tshachu'])],
            'price_per_session' => ['required', 'numeric', 'min:0'],
            'price_per_hour' => ['nullable', 'numeric', 'min:0'],
            'max_guests' => ['required', 'integer', 'min:1'],
            'opening_time' => ['required', 'date_format:H:i'],
            'closing_time' => ['required', 'date_format:H:i'],
            'owner_id' => ['required', 'exists:users,id'],
            'dzongkhag_id' => ['required', 'exists:dzongkhags,id'],
        ]);

        $bath->update($validated);

        return back()->with('success', 'Bath details updated successfully.');
    }

    /**
     * Delete a bath
     */
    public function deleteBath(Bath $bath): RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $bathName = $bath->name;
        $bath->delete();

        return back()->with('success', "Bath '{$bathName}' deleted successfully.");
    }

    // ===========================================
    // COMMISSION MANAGEMENT SECTION
    // ===========================================

    /**
     * Show commission management dashboard
     */
    public function manageCommissions(Request $request): View|RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        // Get commission data for all owners
        $commissionRate = 0.15; // 15% commission
        $owners = User::where('role', 'owner')
            ->with('baths')
            ->orderBy('name')
            ->get();

        $commissionData = $owners->map(function ($owner) use ($commissionRate) {
            // Get all bookings for this owner's baths
            $totalRevenue = Transaction::query()
                ->whereHas('booking', function ($q) use ($owner) {
                    $q->whereHas('bath', function ($q2) use ($owner) {
                        $q2->where('owner_id', $owner->id);
                    });
                })
                ->where('status', 'success')
                ->sum('amount') ?? 0;

            $commission = $totalRevenue * $commissionRate;
            $ownerEarnings = $totalRevenue - $commission;

            return [
                'owner_id' => $owner->id,
                'owner_name' => $owner->name,
                'email' => $owner->email,
                'total_bookings' => Booking::query()
                    ->whereHas('bath', function ($q) use ($owner) {
                        $q->where('owner_id', $owner->id);
                    })
                    ->count(),
                'total_revenue' => $totalRevenue,
                'commission' => $commission,
                'owner_earnings' => $ownerEarnings,
            ];
        })->sortByDesc('total_revenue');

        // Overall stats
        $totalRevenue = Transaction::where('status', 'success')->sum('amount') ?? 0;
        $totalCommission = $totalRevenue * $commissionRate;

        return view('web.admin.commissions', compact('commissionData', 'totalRevenue', 'totalCommission', 'commissionRate'));
    }

    /**
     * Generate commission report (daily/weekly/monthly)
     */
    public function generateCommissionReport(Request $request): View|RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $period = $request->input('period', 'monthly'); // daily, weekly, monthly
        $commissionRate = 0.15;

        $query = Transaction::where('status', 'success');

        if ($period === 'daily') {
            $query->whereDate('created_at', now()->toDateString());
        } elseif ($period === 'weekly') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } else {
            // monthly
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }

        $transactions = $query->with(['user', 'booking'])->latest()->get();
        $totalRevenue = $transactions->sum('amount');
        $totalCommission = $totalRevenue * $commissionRate;
        $ownerEarnings = $totalRevenue - $totalCommission;

        return view('web.admin.commission-report', compact(
            'transactions',
            'totalRevenue',
            'totalCommission',
            'ownerEarnings',
            'period',
            'commissionRate'
        ));
    }

    // ===========================================
    // NOTIFICATIONS & MESSAGES SECTION
    // ===========================================

    /**
     * Send notification to users
     */
    public function sendNotification(Request $request): RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'recipient_type' => ['required', Rule::in(['all_owners', 'all_customers', 'all_users'])],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        // Determine recipients
        $recipientType = $validated['recipient_type'];
        $query = User::query();

        if ($recipientType === 'all_owners') {
            $query->where('role', 'owner');
        } elseif ($recipientType === 'all_customers') {
            $query->where('role', 'guest');
        }
        // all_users gets all

        $recipients = $query->pluck('id')->toArray();

        // TODO: Implement notification system to send messages to users
        // For now, you can store notifications in a notifications table

        return back()->with('success', 'Notification sent to ' . count($recipients) . ' users.');
    }

    /**
     * View admin messages from owners and customers
     */
    public function viewMessages(Request $request): View|RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $messages = \App\Models\Message::query()
            ->with(['booking.guest', 'booking.bath.owner', 'sender', 'recipient'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('web.admin.messages', compact('messages'));
    }

    // ===========================================
    // SETTINGS SECTION
    // ===========================================

    /**
     * Show admin settings page
     */
    public function showSettings(): View|RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $bathTypes = ['menchu', 'dotsho', 'tshachu'];
        $commissionRate = 0.15; // Get from config or env

        return view('web.admin.settings', compact('bathTypes', 'admin', 'commissionRate'));
    }

    /**
     * Update admin profile
     */
    public function updateAdminProfile(Request $request): RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($admin->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? $admin->phone,
            'address' => $validated['address'] ?? $admin->address,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $admin->update($updateData);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update system-wide settings
     */
    public function updateSystemSettings(Request $request): RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'max_guests_default' => ['required', 'integer', 'min:1'],
            'booking_time_limit' => ['required', 'integer', 'min:1'],
            'enable_notifications' => ['nullable', 'boolean'],
        ]);

        // TODO: Store these settings in a settings table or config
        // For now, return success message
        
        return back()->with('success', 'System settings updated successfully.');
    }

    // ===========================================
    // REVIEWS & FEEDBACK SECTION
    // ===========================================

    /**
     * Manage all reviews
     */
    public function manageReviews(Request $request): View|RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $query = \App\Models\Review::query()
            ->with(['guest', 'bath.owner'])
            ->orderBy('created_at', 'desc');

        // Filter by bath
        if ($request->filled('bath_id')) {
            $query->where('bath_id', $request->input('bath_id'));
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->input('rating'));
        }

        // Filter by approval status
        if ($request->filled('approval_status')) {
            $approvalStatus = $request->input('approval_status');
            $query->where('is_approved', intval($approvalStatus));
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        $reviews = $query->paginate(15);
        $baths = Bath::orderBy('name')->get();

        return view('web.admin.reviews', compact('reviews', 'baths'));
    }

    /**
     * Approve a review
     */
    public function approveReview(\App\Models\Review $review): RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $review->update([
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Review approved successfully and will now be visible to customers.');
    }

    /**
     * Reject/remove a review
     */
    public function rejectReview(Request $request, \App\Models\Review $review): RedirectResponse
    {
        $admin = Auth::user();
        if (! $admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $reviewAuthor = $review->guest->name ?? 'Anonymous';
        $review->delete();

        return back()->with('success', "Review from {$reviewAuthor} has been removed successfully.");
    }
}
