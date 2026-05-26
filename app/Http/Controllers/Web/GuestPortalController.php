<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bath;
use App\Models\BathService;
use App\Models\Booking;
use App\Models\Dzongkhag;
use App\Models\Transaction;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Mail\UserRegistrationConfirmationMail;
use App\Mail\NewUserRegistrationMail;

class GuestPortalController extends Controller
{
    public function home(Request $request): View
    {
        $query = Bath::query()
            ->with([
                'dzongkhag',
                'images',
                'services' => function ($q) {
                    $q->where('is_available', true)->where('approval_status', 'approved');
                },
            ])
            ->where(function ($q) {
                $q->where('status', 'active')
                    ->orWhereHas('services', function ($sq) {
                        $sq->where('is_available', true)->where('approval_status', 'approved');
                    });
            });

        if ($request->filled('keyword')) {
            $keyword = trim((string) $request->input('keyword'));
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('short_description', 'like', "%{$keyword}%")
                    ->orWhere('full_address', 'like', "%{$keyword}%")
                    ->orWhereHas('services', function ($serviceQuery) use ($keyword) {
                        $serviceQuery
                            ->where('is_available', true)
                            ->where('approval_status', 'approved')
                            ->where(function ($sq) use ($keyword) {
                                $sq->where('service_type', 'like', "%{$keyword}%")
                                    ->orWhere('description', 'like', "%{$keyword}%")
                                    ->orWhere('location', 'like', "%{$keyword}%");
                            });
                    });
            });
        }

        $selectedDzongkhagId = $request->filled('dzongkhag_id')
            ? (int) $request->input('dzongkhag_id')
            : null;

        if ($selectedDzongkhagId) {
            $query->where(function ($q) use ($selectedDzongkhagId) {
                $q->where('dzongkhag_id', $selectedDzongkhagId)
                    ->orWhereHas('services', function ($sq) use ($selectedDzongkhagId) {
                        $sq->where('is_available', true)
                            ->where('approval_status', 'approved')
                            ->where('dzongkhag_id', $selectedDzongkhagId);
                    });
            });
        }

        $baths = $query->latest()->paginate(9)->withQueryString();
        $dzongkhags = Dzongkhag::query()->orderBy('name')->get();

        // Featured services now come from real approved services so owners see exact approved data.
        $featuredServicesQuery = BathService::query()
            ->with(['bath.dzongkhag', 'bath.images', 'dzongkhag'])
            ->where('is_available', true)
            ->where('approval_status', 'approved')
            ->whereHas('bath', function ($q) {
                $q->where(function ($bathQuery) {
                    $bathQuery->where('status', 'active')
                        ->orWhereHas('services', function ($sq) {
                            $sq->where('is_available', true)->where('approval_status', 'approved');
                        });
                });
            });

        if ($request->filled('keyword')) {
            $keyword = trim((string) $request->input('keyword'));
            $featuredServicesQuery->where(function ($q) use ($keyword) {
                $q->where('service_type', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('location', 'like', "%{$keyword}%")
                    ->orWhereHas('bath', function ($bq) use ($keyword) {
                        $bq->where('name', 'like', "%{$keyword}%")
                            ->orWhere('full_address', 'like', "%{$keyword}%")
                            ->orWhere('short_description', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($selectedDzongkhagId) {
            $featuredServicesQuery->where(function ($q) use ($selectedDzongkhagId) {
                $q->where('dzongkhag_id', $selectedDzongkhagId)
                    ->orWhereHas('bath', function ($bathQuery) use ($selectedDzongkhagId) {
                        $bathQuery->where('dzongkhag_id', $selectedDzongkhagId);
                    });
            });
        }

        $featuredServices = $featuredServicesQuery
            ->latest('reviewed_at')
            ->latest('id')
            ->limit(8)
            ->get();

        // Get latest/newly added services from all active baths
        $latestServicesQuery = BathService::query()
            ->with(['bath.dzongkhag', 'bath.images', 'dzongkhag'])
            ->where('is_available', true)
            ->where('approval_status', 'approved')
            ->whereHas('bath', function ($q) {
                $q->where(function ($bathQuery) {
                    $bathQuery->where('status', 'active')
                        ->orWhereHas('services', function ($sq) {
                            $sq->where('is_available', true)->where('approval_status', 'approved');
                        });
                });
            })
            ->latest('created_at')
            ->limit(12);

        if ($selectedDzongkhagId) {
            $latestServicesQuery->where(function ($q) use ($selectedDzongkhagId) {
                $q->where('dzongkhag_id', $selectedDzongkhagId)
                    ->orWhereHas('bath', function ($bathQuery) use ($selectedDzongkhagId) {
                        $bathQuery->where('dzongkhag_id', $selectedDzongkhagId);
                    });
            });
        }
        
        $latestServices = $latestServicesQuery->get();
        
        // If featured services is empty, use latest services instead
        if ($featuredServices->isEmpty()) {
            $featuredServices = $latestServices;
        }

        // Map service types to local images
        $serviceImages = [
            'Traditional Hotstone Bath' => '/image/Traditional Hotstone Bath.jpg',
            'Herbal Hotstone' => '/image/Herbal Hotstone.jpg',
            'Medicinal Water Bath' => '/image/Medicinal Water Bath.jpg',
            'Natural Hot Spring Water Bath' => '/image/Duenmang Hot Spring.jpg',
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
            'Chencho Farmhouse Hotstone Bath' => '/image/Chencho Farm House Hot Stone Bath.webp',
            'Sacred River Bath House' => '/image/Punakha Valley Herbal Bath.jpg',
            'Wangdue Riverside Hot Stone Bath' => '/image/Wangdue Riverside Hot Stone Bath.jpg',
            'Chhukha Mineral Bath House' => '/image/Chhukha Mineral Bath House.jpg',
            'Bumthang Premium Stone Bath' => '/image/Bumthang Premium Stone Bath.jpg',
            'Trongsa Heritage Bath Center' => '/image/Trongsa Heritage Bath Center.jpg',
            'Mongar Wellness Retreat' => '/image/Mongar Wellness Retreat.jpg',
            'Dhongphangma Menchu' => '/image/Oil Bath.jpg',
            'Khabtey Menchu' => '/image/Khabtey Menchu.jpg',
            'Chhukha Natural Hot Spring Sanctuary' => '/image/Duenmang Hot Spring.jpg',
            'Bumthang Medicinal Healing Bath House' => '/image/Medicinal Water Bath.jpg',
            'Duenmang Hot Spring' => '/image/Duenmang Hot Spring.jpg',
            'Gomphu Kora Hot Stone Bath' => '/image/Gomphukora Hotstone.jpeg',
        ];

        return view('web.home', compact('baths', 'dzongkhags', 'featuredServices', 'latestServices', 'serviceImages', 'bathImages'));
    }

    public function showBath(Bath $bath, Request $request): View
    {
        $hasApprovedService = $bath->services()
            ->where('is_available', true)
            ->where('approval_status', 'approved')
            ->exists();

        if ($bath->status !== 'active' && ! $hasApprovedService) {
            abort(404);
        }

        $bath->load([
            'dzongkhag',
            'images',
            'facilities',
            'availabilities',
            'services' => function ($q) {
                $q->where('is_available', true)->where('approval_status', 'approved');
            },
            'reviews.guest',
        ]);

        // Map service types to local images
        $serviceImages = [
            'Traditional Hotstone Bath' => '/image/Traditional Hotstone Bath.jpg',
            'Herbal Hotstone' => '/image/Herbal Hotstone.jpg',
            'Medicinal Water Bath' => '/image/Medicinal Water Bath.jpg',
            'Natural Hot Spring Water Bath' => '/image/Duenmang Hot Spring.jpg',
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
            'Chencho Farmhouse Hotstone Bath' => '/image/Chencho Farm House Hot Stone Bath.webp',
            'Sacred River Bath House' => '/image/Punakha Valley Herbal Bath.jpg',
            'Wangdue Riverside Hot Stone Bath' => '/image/Wangdue Riverside Hot Stone Bath.jpg',
            'Chhukha Mineral Bath House' => '/image/Chhukha Mineral Bath House.jpg',
            'Bumthang Premium Stone Bath' => '/image/Bumthang Premium Stone Bath.jpg',
            'Trongsa Heritage Bath Center' => '/image/Trongsa Heritage Bath Center.jpg',
            'Mongar Wellness Retreat' => '/image/Mongar Wellness Retreat.jpg',
            'Dhongphangma Menchu' => '/image/Oil Bath.jpg',
            'Khabtey Menchu' => '/image/Khabtey Menchu.jpg',
            'Chhukha Natural Hot Spring Sanctuary' => '/image/Duenmang Hot Spring.jpg',
            'Bumthang Medicinal Healing Bath House' => '/image/Medicinal Water Bath.jpg',
            'Duenmang Hot Spring' => '/image/Duenmang Hot Spring.jpg',
            'Gomphu Kora Hot Stone Bath' => '/image/Gomphukora Hotstone.jpeg',
        ];

        $selectedService = $request->input('service');

        return view('web.bath-details', compact('bath', 'serviceImages', 'selectedService', 'bathImages'));
    }

    public function showLogin(Request $request): View
    {
        $bathId = $request->input('bath_id');
        return view('web.auth.guest-login', ['bathId' => $bathId]);
    }

    public function showRegister(): View
    {
        $dzongkhags = Dzongkhag::query()->orderBy('name')->get();
        return view('web.auth.guest-register', compact('dzongkhags'));
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'dzongkhag_id' => ['nullable', 'exists:dzongkhags,id'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Get dzongkhag name if selected
        $address = null;
        if (!empty($validated['dzongkhag_id'])) {
            $dzongkhag = Dzongkhag::find($validated['dzongkhag_id']);
            $address = $dzongkhag ? $dzongkhag->name : null;
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $address,
            'password' => Hash::make($validated['password']),
            'role' => 'guest',
            'status' => 'active',
        ]);

        // Send confirmation email to the new user
        try {
            Mail::to($user->email)->send(new \App\Mail\UserRegistrationConfirmationMail($user));
        } catch (\Exception $e) {
            \Log::error('Failed to send user confirmation email: ' . $e->getMessage());
        }

        // Send notification to all admins about new user registration
        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new \App\Mail\NewUserRegistrationMail($user));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send admin notification: ' . $e->getMessage());
        }

        Auth::login($user);

        return redirect()->route('guest.dashboard')->with('success', '✅ Registration successful! Welcome to your dashboard. You can start booking now.');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Check if user exists first
        $user = User::where('email', $credentials['email'])->first();

        if (! $user) {
            // User not registered
            return back()
                ->withInput()
                ->with('error', '❌ You are not registered. Please create an account first.');
        }

        // Check if user account is active
        if ($user->status !== 'active') {
            return back()
                ->withInput()
                ->with('error', '❌ Your account is not active. Please contact support.');
        }

        if (! Auth::attempt($credentials)) {
            // Password is incorrect
            return back()
                ->withInput()
                ->with('error', '❌ Invalid credentials. Password is incorrect.');
        }

        $authUser = Auth::user();
        if (! $authUser || $authUser->role !== 'guest') {
            Auth::logout();
            return back()->withInput()->with('error', 'This login is for guest accounts only.');
        }

        $request->session()->regenerate();

        // Check if user came from a bath booking page
        $bathId = $request->input('bath_id');
        if ($bathId) {
            $bath = Bath::find($bathId);
            if ($bath) {
                return redirect()->route('guest.booking.create', $bath)
                    ->with('success', '✅ Login successful! Now complete your booking.');
            }
        }

        return redirect()->route('guest.dashboard')->with('success', '✅ Login successful. Welcome back!');
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

        $hasApprovedService = $bath->services()
            ->where('is_available', true)
            ->where('approval_status', 'approved')
            ->exists();

        if ($bath->status !== 'active' && ! $hasApprovedService) {
            return redirect()->route('home')->with('error', 'Bath is currently unavailable.');
        }

        $bath->load([
            'services' => function ($q) {
                $q->where('is_available', true)->where('approval_status', 'approved');
            },
            'availabilities',
            'facilities',
            'images',
            'dzongkhag',
        ]);

        // Map bath names to their images
        $bathImages = [
            'Thimphu Wellness Stone Spa' => '/image/Thimphu Wellness Stone Spa.jpg',
            'Paro Traditional Hot Stone Bath' => '/image/Paro Traditional Hot Stone Bath.jpg',
            'Chencho Farmhouse Hotstone Bath' => '/image/Chencho Farm House Hot Stone Bath.webp',
            'Sacred River Bath House' => '/image/Punakha Valley Herbal Bath.jpg',
            'Wangdue Riverside Hot Stone Bath' => '/image/Wangdue Riverside Hot Stone Bath.jpg',
            'Chhukha Mineral Bath House' => '/image/Chhukha Mineral Bath House.jpg',
            'Bumthang Premium Stone Bath' => '/image/Bumthang Premium Stone Bath.jpg',
            'Trongsa Heritage Bath Center' => '/image/Trongsa Heritage Bath Center.jpg',
            'Mongar Wellness Retreat' => '/image/Mongar Wellness Retreat.jpg',
            'Dhongphangma Menchu' => '/image/Oil Bath.jpg',
            'Khabtey Menchu' => '/image/Khabtey Menchu.jpg',
            'Chhukha Natural Hot Spring Sanctuary' => '/image/Duenmang Hot Spring.jpg',
            'Bumthang Medicinal Healing Bath House' => '/image/Medicinal Water Bath.jpg',
            'Duenmang Hot Spring' => '/image/Duenmang Hot Spring.jpg',
        ];

        return view('web.guest.create-booking', compact('bath', 'bathImages'));
    }

    public function storeBooking(Request $request, Bath $bath)
    {
        try {
            $user = Auth::user();
            if (! $user || $user->role !== 'guest') {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Please login before booking.'], 401);
                }
                return redirect()->route('guest.login')->with('error', 'Please login before booking.');
            }

            $validated = $request->validate([
                'booking_date' => ['required', 'date', 'after_or_equal:today'],
                'start_time' => ['required', 'date_format:H:i'],
                'number_of_guests' => ['required', 'integer', 'min:1'],
                'payment_method' => ['required', 'in:digital,cash_on_arrival'],
                'banking_app' => ['nullable', 'string'],
                'special_requests' => ['nullable', 'string', 'max:1000'],
            ]);

            $service = BathService::query()
                ->where('bath_id', $bath->id)
                ->where('is_available', true)
                ->where('approval_status', 'approved')
                ->first();

            if (! $service) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'No approved service is available for this bath yet. Please try another bath.'], 422);
                }
                return back()->withInput()->with('error', 'No approved service is available for this bath yet.');
            }

            if ((int) $validated['number_of_guests'] > (int) $bath->max_guests) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Selected guests exceed maximum capacity for this bath.'], 422);
                }
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

            // Send notification email to bath owner (async - don't block response)
            if ($bath->owner && $bath->owner->email) {
                try {
                    Mail::send('emails.booking-notification', [
                        'booking' => $booking,
                        'bath' => $bath,
                        'guest' => $user,
                    ], function ($message) use ($bath) {
                        $message->to($bath->owner->email)
                                ->subject('New Booking Request - ' . $bath->name);
                    });
                } catch (\Exception $e) {
                    // Log the error but don't fail the booking creation
                    \Log::error('Failed to send booking notification email: ' . $e->getMessage());
                }
            }

            // Return appropriate response based on request type
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Booking confirmed successfully!',
                    'booking_id' => $booking->booking_id,
                ], 200);
            }

            return redirect()->route('guest.booking.summary', $booking)->with('success', 'Booking created successfully.');
        } catch (\Exception $e) {
            \Log::error('Booking error: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
            }
            return back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
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

    public function showInquiryForm(Bath $bath): View
    {
        $hasApprovedService = $bath->services()
            ->where('is_available', true)
            ->where('approval_status', 'approved')
            ->exists();

        if ($bath->status !== 'active' && ! $hasApprovedService) {
            abort(404);
        }

        $bath->load(['availabilities', 'dzongkhag']);

        return view('web.bath-inquiry', compact('bath'));
    }

    public function storeInquiry(Request $request, Bath $bath): RedirectResponse
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'number_of_persons' => ['required', 'integer', 'min:1', 'max:' . $bath->max_guests],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['required', 'string'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
        ]);

        // Get or create a service for the bath
        $service = BathService::query()
            ->where('bath_id', $bath->id)
            ->where('is_available', true)
            ->where('approval_status', 'approved')
            ->first();

        if (! $service) {
            return back()->withInput()->with('error', 'This bath has no approved service yet. Please try again later.');
        }

        // Generate inquiry ID
        $inquiryId = 'INQ-' . date('Ymd') . '-' . str_pad(
            Booking::whereDate('created_at', today())->count() + 1,
            5,
            '0',
            STR_PAD_LEFT
        );

        // Calculate estimated price
        $estimatedPrice = $validated['number_of_persons'] * ($bath->price_per_session ?? $bath->price_per_hour);

        // Parse preferred_time - it's now in H:i format (e.g., "09:00")
        $startTime = $validated['preferred_time'];
        $endTime = date('H:i', strtotime($startTime) + (60 * 60)); // 1 hour duration

        // Store inquiry in bookings table or create a new inquiries table
        // For now, we'll store it in a way that can be tracked
        $booking = Booking::create([
            'booking_id' => $inquiryId,
            'bath_id' => $bath->id,
            'service_id' => $service->id,
            'guest_id' => $user->id,
            'guest_name' => $validated['full_name'],
            'guest_email' => $validated['email'],
            'guest_phone' => $validated['phone_number'],
            'booking_date' => $validated['preferred_date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'number_of_guests' => $validated['number_of_persons'],
            'total_price' => $estimatedPrice,
            'status' => 'pending',
            'payment_status' => 'pending',
            'special_requests' => $validated['special_requests'] ?? null,
        ]);

        // In a real application, you would:
        // 1. Send confirmation email to the customer
        // 2. Send notification email to bath owner
        // 3. Store in a dedicated inquiries table

        return redirect()->route('home')->with('success', 
            'Thank you! Your inquiry has been submitted. We\'ll contact you shortly at ' . $validated['email'] . ' to confirm your booking.'
        );
    }
}
