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
use Illuminate\Support\Facades\Storage;
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
        // Validation for simplified registration form
        $validated = $request->validate([
            // Personal Information
            'owner_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            
            // Bath Information
            'bath_type' => ['required', 'in:menchu,dotsho,tshachu'],
            'dzongkhag_id' => ['required', 'exists:dzongkhags,id'],
            'address' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'facilities' => ['required', 'array', 'min:1'],
            'facilities.*' => ['string', 'max:100'],
            
            // Operational Details
            'opening_time' => ['required', 'date_format:H:i'],
            'closing_time' => ['required', 'date_format:H:i'],
            
            // Pricing & Capacity
            'price_per_session' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'max_guests' => ['required', 'integer', 'min:1', 'max:100'],
            
            // Security
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            $owner = User::create([
                'name' => $validated['owner_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'password' => Hash::make($validated['password']),
                'role' => 'owner',
                'status' => 'pending_verification',
            ]);

            // Create bath
            $bath = Bath::create([
                'owner_id' => $owner->id,
                'name' => $validated['bath_type'],
                'property_type' => 'hot_stone_bath',
                'dzongkhag_id' => (int) $validated['dzongkhag_id'],
                'full_address' => $validated['address'],
                'short_description' => $validated['description'],
                'detailed_description' => $validated['description'],
                'tourism_license_number' => 'PENDING-' . strtoupper(substr(md5($validated['bath_type'] . now()), 0, 10)),
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

            // Create default availability (Monday-Sunday)
            $dayMap = ['Monday' => 0, 'Tuesday' => 1, 'Wednesday' => 2, 'Thursday' => 3, 'Friday' => 4, 'Saturday' => 5, 'Sunday' => 6];
            foreach ($dayMap as $day => $dayOfWeek) {
                Availability::create([
                    'bath_id' => $bath->id,
                    'day_of_week' => $dayOfWeek,
                    'opening_time' => $validated['opening_time'],
                    'closing_time' => $validated['closing_time'],
                    'is_open' => true,
                ]);
            }

            // Create facilities
            if (!empty($validated['facilities'])) {
                foreach ($validated['facilities'] as $facility) {
                    BathFacility::create([
                        'bath_id' => $bath->id,
                        'facility_name' => $facility,
                        'description' => null,
                        'is_available' => true,
                    ]);
                }
            }

            return redirect()->route('owner.login')->with('success', 'Registration submitted successfully! Please wait for admin approval before logging in.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Registration failed: ' . $e->getMessage());
        }
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

        if (! in_array($user->status, ['active', 'approved'], true)) {
            Auth::logout();
            return back()->withInput()->with('error', 'Your owner account is pending admin approval.');
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

        $bath = Bath::query()->with(['dzongkhag', 'facilities', 'availabilities', 'services'])->where('owner_id', $user->id)->first();
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
            'status' => $validated['status'] ?? 'active',
        ]);
        $bath->save();

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

    public function showServiceForm(): View|RedirectResponse
    {
        $owner = Auth::user();
        if (! $owner || $owner->role !== 'owner') {
            return redirect()->route('owner.login');
        }

        $bath = $owner->baths()->first();
        if (! $bath) {
            return redirect()->route('owner.listing.form')->with('error', 'Please add a bath listing first before adding services.');
        }

        $serviceTypes = [
            'Traditional Hotstone Bath',
            'Herbal Hotstone',
            'Medicinal Water Bath',
            'Natural Hot Spring Water Bath',
            'Oil Bath',
            'Herbal Steam / Wellness Bath',
            'Foot Bath',
            'Relaxing Hot Stone Bath',
            'Detox Steam Bath',
        ];

        $existingServices = $bath->services()->pluck('service_type')->toArray();
        $dzongkhags = Dzongkhag::query()->orderBy('name')->get();

        return view('web.owner.add-service', compact('bath', 'serviceTypes', 'existingServices', 'dzongkhags'));
    }

    public function saveService(Request $request): RedirectResponse
    {
        $owner = Auth::user();
        if (! $owner || $owner->role !== 'owner') {
            return redirect()->route('owner.login');
        }

        $bath = $owner->baths()->first();
        if (! $bath) {
            return redirect()->route('owner.listing.form')->with('error', 'Bath listing not found.');
        }

        $validated = $request->validate([
            'service_type' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'dzongkhag_id' => ['required', 'exists:dzongkhags,id'],
            'location' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:15'],
            'is_available' => ['boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'opening_time' => ['nullable', 'date_format:H:i'],
            'closing_time' => ['nullable', 'date_format:H:i'],
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('services', 'public');
        }

        $bath->services()->create([
            'dzongkhag_id' => (int) $validated['dzongkhag_id'],
            'service_type' => $validated['service_type'],
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'] ?? null,
            'price' => $validated['price'],
            'duration_minutes' => $validated['duration_minutes'],
            'max_guests' => $bath->max_guests,
            'is_available' => $validated['is_available'] ?? true,
            'approval_status' => 'pending',
            'approval_notes' => null,
            'reviewed_at' => null,
            'image' => $imagePath,
            'opening_time' => $validated['opening_time'] ?? null,
            'closing_time' => $validated['closing_time'] ?? null,
        ]);

        return redirect()->route('owner.dashboard')->with('success', 'Service submitted successfully and is pending admin approval.');
    }

    public function showEditServiceForm(BathService $service): View|RedirectResponse
    {
        $owner = Auth::user();
        if (! $owner || $owner->role !== 'owner') {
            return redirect()->route('owner.login');
        }

        $bath = $owner->baths()->first();
        if (! $bath || (int) $service->bath_id !== (int) $bath->id) {
            abort(403);
        }

        $serviceTypes = [
            'Traditional Hotstone Bath',
            'Herbal Hotstone',
            'Medicinal Water Bath',
            'Natural Hot Spring Water Bath',
            'Oil Bath',
            'Herbal Steam / Wellness Bath',
            'Foot Bath',
            'Relaxing Hot Stone Bath',
            'Detox Steam Bath',
        ];

        $dzongkhags = Dzongkhag::query()->orderBy('name')->get();

        return view('web.owner.edit-service', compact('bath', 'service', 'serviceTypes', 'dzongkhags'));
    }

    public function updateService(Request $request, BathService $service): RedirectResponse
    {
        $owner = Auth::user();
        if (! $owner || $owner->role !== 'owner') {
            return redirect()->route('owner.login');
        }

        $bath = $owner->baths()->first();
        if (! $bath || (int) $service->bath_id !== (int) $bath->id) {
            abort(403);
        }

        $validated = $request->validate([
            'service_type' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'dzongkhag_id' => ['required', 'exists:dzongkhags,id'],
            'location' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:15'],
            'is_available' => ['boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'opening_time' => ['nullable', 'date_format:H:i'],
            'closing_time' => ['nullable', 'date_format:H:i'],
        ]);

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $service->image = $request->file('image')->store('services', 'public');
        }

        $service->fill([
            'dzongkhag_id' => (int) $validated['dzongkhag_id'],
            'service_type' => $validated['service_type'],
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'] ?? null,
            'price' => $validated['price'],
            'duration_minutes' => $validated['duration_minutes'],
            'is_available' => $validated['is_available'] ?? false,
            'opening_time' => $validated['opening_time'] ?? null,
            'closing_time' => $validated['closing_time'] ?? null,
            // Any owner edit should go back for admin review.
            'approval_status' => 'pending',
            'approval_notes' => 'Updated by owner. Pending admin review.',
            'reviewed_at' => null,
        ]);

        $service->save();

        return redirect()->route('owner.dashboard')->with('success', 'Service updated and submitted for admin review.');
    }

    public function deleteService(BathService $service): RedirectResponse
    {
        $owner = Auth::user();
        if (! $owner || $owner->role !== 'owner') {
            return redirect()->route('owner.login');
        }

        $bath = $owner->baths()->first();
        if (! $bath || (int) $service->bath_id !== (int) $bath->id) {
            abort(403);
        }

        if ($service->bookings()->exists()) {
            return back()->with('error', 'This service cannot be deleted because it has bookings.');
        }

        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return back()->with('success', 'Service deleted successfully.');
    }

    public function flagService(Request $request, BathService $service): RedirectResponse
    {
        $owner = Auth::user();
        if (! $owner || $owner->role !== 'owner') {
            return redirect()->route('owner.login');
        }

        $bath = $owner->baths()->first();
        if (! $bath || (int) $service->bath_id !== (int) $bath->id) {
            abort(403);
        }

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $service->update([
            'approval_status' => 'pending',
            'approval_notes' => $validated['reason'] ?: 'Flagged by owner for admin review.',
            'reviewed_at' => null,
            'is_available' => false,
        ]);

        return back()->with('success', 'Service flagged and sent to admin for review.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
