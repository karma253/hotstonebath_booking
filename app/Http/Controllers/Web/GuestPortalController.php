<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bath;
use App\Models\BathService;
use App\Models\Booking;
use App\Models\Dzongkhag;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class GuestPortalController extends Controller
{
    public function home(Request $request): View
    {
        $query = Bath::query()
            ->with(['dzongkhag', 'images'])
            ->where('status', 'active');

        if ($request->filled('keyword')) {
            $keyword = trim((string) $request->input('keyword'));
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('short_description', 'like', "%{$keyword}%")
                    ->orWhere('full_address', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('dzongkhag_id')) {
            $query->where('dzongkhag_id', (int) $request->input('dzongkhag_id'));
        }

        $baths = $query->latest()->paginate(9)->withQueryString();
        $dzongkhags = Dzongkhag::query()->orderBy('name')->get();

        // Map each service type to a specific dzongkhag for featured display
        $serviceTypeDzongkhagMap = [
            'Traditional Hotstone Bath' => 'Thimphu',
            'Herbal Hotstone' => 'Paro',
            'Medicinal Water Bath' => 'Punakha',
            'Oil Bath' => 'Wangdue Phodrang',
            'Herbal Steam / Wellness Bath' => 'Chhukha',
            'Foot Bath' => 'Bumthang',
            'Relaxing Hot Stone Bath' => 'Trongsa',
            'Detox Steam Bath' => 'Mongar',
        ];

        // Get featured services from specific dzongkhags
        $featuredServices = collect();
        foreach ($serviceTypeDzongkhagMap as $serviceType => $dzongkhagName) {
            $service = BathService::query()
                ->with(['bath.dzongkhag', 'bath.images'])
                ->where('service_type', $serviceType)
                ->where('is_available', true)
                ->whereHas('bath', function ($q) use ($dzongkhagName) {
                    $q->whereHas('dzongkhag', function ($dq) use ($dzongkhagName) {
                        $dq->where('name', $dzongkhagName);
                    })
                    ->where('status', 'active');
                })
                ->first();

            if ($service) {
                $featuredServices->push($service);
            }
        }

        // Map service types to local images
        $serviceImages = [
            'Traditional Hotstone Bath' => '/image/Traditional Hotstone Bath.jpg',
            'Herbal Hotstone' => '/image/Herbal Hotstone.jpg',
            'Medicinal Water Bath' => '/image/Medicinal Water Bath.jpg',
            'Oil Bath' => '/image/Oil Bath.jpg',
            'Herbal Steam / Wellness Bath' => '/image/Herbal Steam - Wellness Bath.jpg',
            'Foot Bath' => '/image/Foot Bath.jpg',
            'Relaxing Hot Stone Bath' => '/image/Relaxing Hot Stone Bath.jpg',
            'Detox Steam Bath' => '/image/detox steam bath.jpg',
        ];

        // Map bath names to their images
        $bathImages = [
            'Thimphu Wellness Stone Spa' => '/image/Thimphu Wellness Stone Spa.jpg',
            'Paro Traditional Hot Stone Bath' => '/image/Paro Traditional Hot Stone Bath.jpg',
            'Punakha Valley Herbal Bath' => '/image/Punakha Valley Herbal Bath.jpg',
            'Wangdue Riverside Hot Stone Bath' => '/image/Wangdue Riverside Hot Stone Bath.jpg',
            'Chhukha Mineral Bath House' => '/image/Chhukha Mineral Bath House.jpg',
            'Bumthang Premium Stone Bath' => '/image/Bumthang Premium Stone Bath.jpg',
            'Trongsa Heritage Bath Center' => '/image/Trongsa Heritage Bath Center.jpg',
            'Mongar Wellness Retreat' => '/image/Mongar Wellness Retreat.jpg',
        ];

        return view('web.home', compact('baths', 'dzongkhags', 'featuredServices', 'serviceImages', 'bathImages'));
    }

    public function showBath(Bath $bath, Request $request): View
    {
        if ($bath->status !== 'active') {
            abort(404);
        }

        $bath->load([
            'dzongkhag',
            'images',
            'facilities',
            'availabilities',
            'services',
            'reviews.guest',
        ]);

        // Map service types to local images
        $serviceImages = [
            'Traditional Hotstone Bath' => '/image/Traditional Hotstone Bath.jpg',
            'Herbal Hotstone' => '/image/Herbal Hotstone.jpg',
            'Medicinal Water Bath' => '/image/Medicinal Water Bath.jpg',
            'Oil Bath' => '/image/Oil Bath.jpg',
            'Herbal Steam / Wellness Bath' => '/image/Herbal Steam - Wellness Bath.jpg',
            'Foot Bath' => '/image/Foot Bath.jpg',
            'Relaxing Hot Stone Bath' => '/image/Relaxing Hot Stone Bath.jpg',
            'Detox Steam Bath' => '/image/detox steam bath.jpg',
        ];

        // Map bath names to their images
        $bathImages = [
            'Thimphu Wellness Stone Spa' => '/image/Thimphu Wellness Stone Spa.jpg',
            'Paro Traditional Hot Stone Bath' => '/image/Paro Traditional Hot Stone Bath.jpg',
            'Punakha Valley Herbal Bath' => '/image/Punakha Valley Herbal Bath.jpg',
            'Wangdue Riverside Hot Stone Bath' => '/image/Wangdue Riverside Hot Stone Bath.jpg',
            'Chhukha Mineral Bath House' => '/image/Chhukha Mineral Bath House.jpg',
            'Bumthang Premium Stone Bath' => '/image/Bumthang Premium Stone Bath.jpg',
            'Trongsa Heritage Bath Center' => '/image/Trongsa Heritage Bath Center.jpg',
            'Mongar Wellness Retreat' => '/image/Mongar Wellness Retreat.jpg',
        ];

        $selectedService = $request->input('service');

        return view('web.bath-details', compact('bath', 'serviceImages', 'selectedService', 'bathImages'));
    }

    public function showLogin(): View
    {
        return view('web.auth.guest-login');
    }

    public function showRegister(): View
    {
        return view('web.auth.guest-register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'guest',
            'status' => 'active',
        ]);

        Auth::login($user);

        return redirect()->route('guest.dashboard')->with('success', 'Registration successful. Welcome!');
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
        if (! $user || $user->role !== 'guest') {
            Auth::logout();
            return back()->withInput()->with('error', 'This login is for guest accounts only.');
        }

        $request->session()->regenerate();

        return redirect()->route('guest.dashboard');
    }

    public function dashboard(): View|RedirectResponse
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'guest') {
            return redirect()->route('guest.login');
        }

        $bookings = Booking::query()
            ->with(['bath', 'service'])
            ->where('guest_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('web.guest.dashboard', compact('bookings'));
    }

    public function createBookingForm(Bath $bath): View|RedirectResponse
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'guest') {
            return redirect()->route('guest.login')->with('error', 'Please login before booking.');
        }

        if ($bath->status !== 'active') {
            return redirect()->route('home')->with('error', 'Bath is currently unavailable.');
        }

        $bath->load(['services', 'availabilities', 'facilities', 'images', 'dzongkhag']);

        return view('web.guest.create-booking', compact('bath'));
    }

    public function storeBooking(Request $request, Bath $bath): RedirectResponse
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'guest') {
            return redirect()->route('guest.login')->with('error', 'Please login before booking.');
        }

        $validated = $request->validate([
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'number_of_guests' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:digital,cash_on_arrival'],
            'banking_app' => ['nullable', 'in:MBoB,MPay,BDBL'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
        ]);

        $service = BathService::query()
            ->where('bath_id', $bath->id)
            ->where('is_available', true)
            ->first();

        if (! $service) {
            $service = BathService::create([
                'bath_id' => $bath->id,
                'service_type' => 'Standard Hot Stone Session',
                'description' => 'Default session created by system',
                'duration_minutes' => 60,
                'price' => $bath->price_per_session ?? $bath->price_per_hour,
                'max_guests' => $bath->max_guests,
                'is_available' => true,
            ]);
        }

        if ((int) $validated['number_of_guests'] > (int) $bath->max_guests) {
            return back()->withInput()->with('error', 'Selected guests exceed maximum capacity for this bath.');
        }

        $sessionPrice = (float) ($bath->price_per_session ?? $service->price ?? $bath->price_per_hour ?? 0);
        $totalPrice = $sessionPrice * (int) $validated['number_of_guests'];
        $bookingDate = Carbon::parse($validated['booking_date']);
        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end = $start->copy()->addMinutes((int) ($service->duration_minutes ?: 60));

        // Build special requests with banking app preference if provided
        $specialRequests = $validated['special_requests'] ?? null;
        if ($validated['banking_app'] ?? null) {
            $bankingAppNote = "Preferred Banking App: {$validated['banking_app']}";
            $specialRequests = $specialRequests ? "$specialRequests\n$bankingAppNote" : $bankingAppNote;
        }

        $booking = Booking::create([
            'booking_id' => 'BOOKING-' . now()->format('Ymd') . '-' . str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT),
            'guest_id' => $user->id,
            'bath_id' => $bath->id,
            'service_id' => $service->id,
            'guest_name' => $user->name,
            'guest_email' => $user->email,
            'guest_phone' => $user->phone ?? 'N/A',
            'booking_date' => $bookingDate,
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'number_of_guests' => (int) $validated['number_of_guests'],
            'total_price' => $totalPrice,
            'payment_method' => $validated['payment_method'] === 'digital' ? 'online' : 'on_site',
            'payment_status' => $validated['payment_method'] === 'digital' ? 'paid' : 'pending',
            'payment_date' => $validated['payment_method'] === 'digital' ? now() : null,
            'status' => 'pending',
            'special_requests' => $specialRequests,
        ]);

        // Create transaction record if digital payment
        if ($validated['payment_method'] === 'digital') {
            Transaction::create([
                'transaction_id' => 'TXN' . now()->format('YmdHis') . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT),
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'payment_method' => $validated['banking_app'] ?? 'unknown',
                'amount' => $totalPrice,
                'status' => 'success',
                'processed_at' => now(),
            ]);
        }

        return redirect()->route('guest.booking.summary', $booking)->with('success', 'Booking created successfully.');
    }

    public function bookingSummary(Booking $booking): View|RedirectResponse
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'guest' || $booking->guest_id !== $user->id) {
            return redirect()->route('guest.login');
        }

        $booking->load(['bath.dzongkhag', 'service']);

        return view('web.guest.booking-summary', compact('booking'));
    }

    public function cancelBooking(Booking $booking): RedirectResponse
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'guest' || $booking->guest_id !== $user->id) {
            return redirect()->route('guest.login');
        }

        if (in_array($booking->status, ['completed', 'cancelled'], true)) {
            return back()->with('error', 'Booking can no longer be cancelled.');
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => 'Cancelled by customer',
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'Booking cancelled successfully.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
