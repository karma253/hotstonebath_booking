<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\Bath;
use App\Models\BathFacility;
use App\Models\BathImage;
use App\Models\BathService;
use App\Models\Booking;
use App\Models\Dzongkhag;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OwnerPortalController extends Controller
{
    public function showLogin(): View
    {
        return view('web.auth.owner-login');
    }

    public function showRegister(): View
    {
        $dzongkhags = Dzongkhag::query()->orderBy('name')->get();
        return view('web.auth.owner-register', compact('dzongkhags'));
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'owner_name' => ['required', 'string', 'max:255'],
            'bath_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'dzongkhag_id' => ['required', 'exists:dzongkhags,id'],
            'address' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'price_per_session' => ['required', 'numeric', 'min:0'],
            'max_guests' => ['required', 'integer', 'min:1'],
            'facilities' => ['nullable', 'string', 'max:1000'],
            'opening_time' => ['required', 'date_format:H:i'],
            'closing_time' => ['required', 'date_format:H:i', 'after:opening_time'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $owner = User::create([
            'name' => $validated['owner_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'password' => Hash::make($validated['password']),
            'role' => 'owner',
            'status' => 'pending_verification',
        ]);

        $bath = Bath::create([
            'owner_id' => $owner->id,
            'name' => $validated['bath_name'],
            'property_type' => 'hot_stone_bath',
            'dzongkhag_id' => (int) $validated['dzongkhag_id'],
            'full_address' => $validated['address'],
            'short_description' => $validated['description'],
            'detailed_description' => $validated['description'],
            'tourism_license_number' => 'PENDING-' . strtoupper(substr(md5($validated['bath_name'] . now()), 0, 10)),
            'issuing_authority' => 'Pending Verification',
            'license_issue_date' => now()->toDateString(),
            'license_expiry_date' => now()->addYear()->toDateString(),
            'license_status' => 'pending',
            'max_guests' => (int) $validated['max_guests'],
            'price_per_hour' => (float) $validated['price_per_session'],
            'price_per_session' => (float) $validated['price_per_session'],
            'booking_type' => 'approval_required',
            'cancellation_policy' => 'Cancellations allowed up to 24 hours before booking time.',
            'status' => 'pending_verification',
        ]);

        BathService::create([
            'bath_id' => $bath->id,
            'service_type' => 'Standard Session',
            'description' => 'Default session for this bath listing.',
            'duration_minutes' => 60,
            'price' => (float) $validated['price_per_session'],
            'max_guests' => (int) $validated['max_guests'],
            'is_available' => true,
        ]);

        if (! empty($validated['facilities'])) {
            $facilities = array_filter(array_map('trim', explode(',', $validated['facilities'])));
            foreach ($facilities as $facility) {
                BathFacility::create([
                    'bath_id' => $bath->id,
                    'facility_name' => $facility,
                    'description' => null,
                    'is_available' => true,
                ]);
            }
        }

        for ($day = 0; $day <= 6; $day++) {
            Availability::updateOrCreate(
                ['bath_id' => $bath->id, 'day_of_week' => $day],
                [
                    'opening_time' => $validated['opening_time'],
                    'closing_time' => $validated['closing_time'],
                    'is_open' => true,
                ]
            );
        }

        BathImage::create([
            'bath_id' => $bath->id,
            'image_path' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=1200',
            'image_type' => 'bath_area',
            'description' => 'Demo image',
            'order' => 1,
            'is_primary' => true,
        ]);

        return redirect()->route('owner.login')->with('success', 'Registration submitted. Admin approval is required before going live.');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()->withInput()->with('error', 'Invalid email or password.');
        }

        $user = Auth::user();
        if (! $user || ! in_array($user->role, ['owner', 'manager'], true)) {
            Auth::logout();
            return back()->withInput()->with('error', 'This login is for bath owners only.');
        }

        if ($user->status === 'rejected') {
            Auth::logout();
            return back()->withInput()->with('error', 'Registration rejected. Please contact admin or resubmit details.');
        }

        $request->session()->regenerate();

        return redirect()->route('owner.dashboard');
    }

    public function dashboard(): View|RedirectResponse
    {
        $user = Auth::user();
        if (! $user || ! in_array($user->role, ['owner', 'manager'], true)) {
            return redirect()->route('owner.login');
        }

        $bath = Bath::query()->with(['dzongkhag', 'facilities', 'availabilities'])->where('owner_id', $user->id)->first();
        $bookings = Booking::query()
            ->with(['bath', 'guest'])
            ->whereHas('bath', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })
            ->latest()
            ->paginate(10);

        return view('web.owner.dashboard', compact('bath', 'bookings'));
    }

    public function showListingForm(): View|RedirectResponse
    {
        $user = Auth::user();
        if (! $user || ! in_array($user->role, ['owner', 'manager'], true)) {
            return redirect()->route('owner.login');
        }

        $dzongkhags = Dzongkhag::query()->orderBy('name')->get();
        $bath = Bath::query()->with(['facilities', 'availabilities'])->where('owner_id', $user->id)->first();

        return view('web.owner.listing-form', compact('bath', 'dzongkhags'));
    }

    public function saveListing(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (! $user || ! in_array($user->role, ['owner', 'manager'], true)) {
            return redirect()->route('owner.login');
        }

        $validated = $request->validate([
            'bath_name' => ['required', 'string', 'max:255'],
            'dzongkhag_id' => ['required', 'exists:dzongkhags,id'],
            'address' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'price_per_session' => ['required', 'numeric', 'min:0'],
            'max_guests' => ['required', 'integer', 'min:1'],
            'facilities' => ['nullable', 'string', 'max:1000'],
            'opening_time' => ['required', 'date_format:H:i'],
            'closing_time' => ['required', 'date_format:H:i', 'after:opening_time'],
            'status' => ['nullable', Rule::in(['pending_verification', 'active', 'inactive'])],
        ]);

        $bath = Bath::query()->firstOrNew(['owner_id' => $user->id]);
        $bath->fill([
            'name' => $validated['bath_name'],
            'property_type' => 'hot_stone_bath',
            'dzongkhag_id' => (int) $validated['dzongkhag_id'],
            'full_address' => $validated['address'],
            'short_description' => $validated['description'],
            'detailed_description' => $validated['description'],
            'tourism_license_number' => $bath->tourism_license_number ?: 'PENDING-' . strtoupper(substr(md5($validated['bath_name'] . now()), 0, 10)),
            'issuing_authority' => $bath->issuing_authority ?: 'Pending Verification',
            'license_issue_date' => $bath->license_issue_date ?: now()->toDateString(),
            'license_expiry_date' => $bath->license_expiry_date ?: now()->addYear()->toDateString(),
            'license_status' => $bath->license_status ?: 'pending',
            'max_guests' => (int) $validated['max_guests'],
            'price_per_hour' => (float) $validated['price_per_session'],
            'price_per_session' => (float) $validated['price_per_session'],
            'booking_type' => 'approval_required',
            'cancellation_policy' => 'Cancellations allowed up to 24 hours before booking time.',
            'status' => $validated['status'] ?? 'pending_verification',
        ]);
        $bath->save();

        BathService::query()->updateOrCreate(
            ['bath_id' => $bath->id, 'service_type' => 'Standard Session'],
            [
                'description' => 'Default session for this bath listing.',
                'duration_minutes' => 60,
                'price' => (float) $validated['price_per_session'],
                'max_guests' => (int) $validated['max_guests'],
                'is_available' => true,
            ]
        );

        BathFacility::query()->where('bath_id', $bath->id)->delete();
        if (! empty($validated['facilities'])) {
            $facilities = array_filter(array_map('trim', explode(',', $validated['facilities'])));
            foreach ($facilities as $facility) {
                BathFacility::create([
                    'bath_id' => $bath->id,
                    'facility_name' => $facility,
                    'description' => null,
                    'is_available' => true,
                ]);
            }
        }

        for ($day = 0; $day <= 6; $day++) {
            Availability::updateOrCreate(
                ['bath_id' => $bath->id, 'day_of_week' => $day],
                [
                    'opening_time' => $validated['opening_time'],
                    'closing_time' => $validated['closing_time'],
                    'is_open' => true,
                ]
            );
        }

        if (! BathImage::query()->where('bath_id', $bath->id)->exists()) {
            BathImage::create([
                'bath_id' => $bath->id,
                'image_path' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=1200',
                'image_type' => 'bath_area',
                'description' => 'Demo image',
                'order' => 1,
                'is_primary' => true,
            ]);
        }

        return redirect()->route('owner.dashboard')->with('success', 'Bath listing updated successfully.');
    }

    public function updateBookingStatus(Request $request, Booking $booking): RedirectResponse
    {
        $user = Auth::user();
        if (! $user || ! in_array($user->role, ['owner', 'manager'], true)) {
            return redirect()->route('owner.login');
        }

        $owned = Bath::query()->where('owner_id', $user->id)->where('id', $booking->bath_id)->exists();
        if (! $owned) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled', 'completed', 'no_show'])],
        ]);

        $payload = ['status' => $validated['status']];
        if ($validated['status'] === 'confirmed') {
            $payload['confirmed_at'] = now();
        }
        if ($validated['status'] === 'completed') {
            $payload['completed_at'] = now();
        }
        if ($validated['status'] === 'cancelled') {
            $payload['cancelled_at'] = now();
            $payload['cancellation_reason'] = 'Cancelled by bath owner';
        }

        $booking->update($payload);

        // Create transaction if booking is confirmed and paid
        if ($validated['status'] === 'confirmed' && $booking->payment_status === 'paid') {
            $existingTransaction = Transaction::where('booking_id', $booking->id)->first();
            
            if (!$existingTransaction) {
                // Determine payment method from booking special requests or payment method
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
                } elseif ($booking->payment_method === 'online') {
                    $paymentMethod = 'digital';
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

        return back()->with('success', 'Booking status updated successfully.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
