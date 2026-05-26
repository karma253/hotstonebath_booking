<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bath;
use App\Models\BathService;
use App\Models\Booking;
use App\Models\Message;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\Service;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use App\Mail\UserRegistrationConfirmationMail;
use App\Mail\NewUserRegistrationMail;

class GuestPortalController extends Controller
{
    public function home(Request $request): View
    {
        try {

        
        // --- existing logic follows ---
        $homepageCategories = [
            'Hot Stone Bath',
            'Medicinal Water',
        ];

        $query = Bath::query()
            ->with([
                'images',
                'services' => function ($q) {
                    $q->where('is_available', true)->where('approval_status', 'approved');
                },
            ])
            ->where('status', 'active');

        if ($request->filled('keyword')) {
            $keyword = trim((string) $request->input('keyword'));
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('short_description', 'like', "%{$keyword}%")
                    ->orWhere('full_address', 'like', "%{$keyword}%");
            });
        }

        // Handle bath type filter
        $selectedBathType = $request->filled('bath_type')
            ? trim((string) $request->input('bath_type'))
            : null;

        if ($selectedBathType) {
            $query->where('bath_type', $selectedBathType);
        }

        $baths = $query->latest()->paginate(9)->appends(request()->query());
        // Featured services now come from real approved services so owners see exact approved data.
        $featuredServicesQuery = BathService::query()
            ->with(['bath.images'])
            ->where('is_available', true)
            ->where('approval_status', 'approved')
            ->whereHas('bath', function ($q) {
                $q->where('status', 'active');
            });

        if ($request->filled('keyword')) {
            $keyword = trim((string) $request->input('keyword'));
            $featuredServicesQuery->where(function ($q) use ($keyword) {
                $q->where('service_type', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('area', 'like', "%{$keyword}%")
                    ->orWhere('full_address', 'like', "%{$keyword}%")
                    ->orWhereHas('bath', function ($bq) use ($keyword) {
                        $bq->where('name', 'like', "%{$keyword}%")
                            ->orWhere('full_address', 'like', "%{$keyword}%")
                            ->orWhere('area', 'like', "%{$keyword}%")
                            ->orWhere('short_description', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($selectedBathType) {
            $featuredServicesQuery->whereHas('bath', function ($q) use ($selectedBathType) {
                $q->where('bath_type', $selectedBathType);
            });
        }

        $featuredServices = $featuredServicesQuery
            ->latest('reviewed_at')
            ->latest('id')
            ->limit(8)
            ->get();

        // Get latest/newly added services from all active baths
        $latestServicesQuery = BathService::query()
            ->with(['bath.images'])
            ->where('is_available', true)
            ->where('approval_status', 'approved')
            ->whereHas('bath', function ($q) {
                $q->where('status', 'active');
            })
            ->latest('created_at')
            ->limit(12);

        if ($selectedBathType) {
            $latestServicesQuery->whereHas('bath', function ($q) use ($selectedBathType) {
                $q->where('bath_type', $selectedBathType);
            });
        }
        
        $latestServices = $latestServicesQuery->get();
        
        // If featured services is empty, use latest services instead
        if ($featuredServices->isEmpty()) {
            $featuredServices = $latestServices;
        }

        // Fetch generic services grouped by category for "Available Wellness Services"
        $groupedServices = Service::query()
            ->where('status', 'approved')
            ->where('is_available', true)
            ->get()
            ->groupBy(function ($service) {
                return $this->normalizeServiceCategory(
                    $service->category ?? null,
                    $service->bath_type ?? null,
                    $service->description ?? null
                );
            });

        $groupedServices = collect($homepageCategories)
            ->mapWithKeys(fn (string $category) => [$category => $groupedServices->get($category, collect())]);

        // Self-heal catalog data by syncing approved owner bath-services into Service.
        BathService::query()
            ->with(['bath'])
            ->where('approval_status', 'approved')
            ->where('is_available', true)
            ->whereHas('bath', function ($q) {
                $q->where('status', 'active');
            })
            ->get()
            ->each(function (BathService $service) {
                $bath = $service->bath;
                if (! $bath) {
                    return;
                }

                $derivedCategory = $this->categoryAndBathTypeFromServiceType($service->service_type);
                $category = $derivedCategory['category'];
                $bathType = $derivedCategory['bath_type'];

                $values = [
                    'bath_type' => $bathType,
                    'facilities' => null,
                    'area' => $service->area ?: $bath->area,
                    'full_address' => $service->full_address ?: $bath->full_address,
                    'max_guests' => (int) ($service->max_guests ?: 1),
                    'opening_time' => $service->opening_time,
                    'closing_time' => $service->closing_time,
                    'image' => $service->image,
                    'status' => 'approved',
                    'is_available' => true,
                ];

                if (Schema::hasColumn('services', 'category')) {
                    $values['category'] = $category;
                }

                Service::query()->updateOrCreate(
                    [
                        'owner_id' => (int) $bath->owner_id,
                        'bath_name' => $bath->name,
                        'description' => $service->description,
                        'price' => $service->price,
                    ],
                    $values
                );
            });

        // Re-read after sync to avoid duplicate in-memory merges.
        $groupedServices = Service::query()
            ->where('status', 'approved')
            ->where('is_available', true)
            ->get()
            ->groupBy(function ($service) {
                return $this->normalizeServiceCategory(
                    $service->category ?? null,
                    $service->bath_type ?? null,
                    $service->description ?? null
                );
            });

        $groupedServices = collect($homepageCategories)
            ->mapWithKeys(fn (string $category) => [$category => $groupedServices->get($category, collect())]);

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

        return view('home', compact('baths', 'featuredServices', 'latestServices', 'serviceImages', 'bathImages', 'groupedServices'));
        
        } catch (\Throwable $e) {
            // Log the exception and return a graceful homepage so users see the UI while DB is down.
            Log::error('GuestPortalController::home failed: ' . $e->getMessage());

            $baths = collect();
            $featuredServices = collect();
            $latestServices = collect();
            $serviceImages = [
                'Hot Stone Bath' => '/image/hot stone bath.png',
                'Herbal Steam Bath' => '/image/Herbal Steam Bath.png',
                'Medicinal Water' => '/image/Medicinal Water Bath.jpg',
                'Spa & Massage Therapy' => '/image/Spa & Massage Therapy.png',
            ];
            $bathImages = [];
            $groupedServices = collect();

            return view('home', compact('baths', 'featuredServices', 'latestServices', 'serviceImages', 'bathImages', 'groupedServices'));
        }
    }

    public function servicesByCategory(string $category): View|RedirectResponse
    {
        $categoryTitle = ucfirst(str_replace('-', ' ', $category));
        $validCategories = ['Hot Stone Bath', 'Medicinal Water'];

        // Normalize category slug to proper title (only allowed categories)
        $categoryMap = [
            'hot-stone-bath' => 'Hot Stone Bath',
            'medicinal-water' => 'Medicinal Water',
        ];

        if (!isset($categoryMap[$category])) {
            return redirect()->route('home')->with('error', 'Category not found.');
        }

        $categoryTitle = $categoryMap[$category];

        // Fetch services for this category
        $services = Service::query()
            ->where('status', 'approved')
            ->where('is_available', true)
            ->get()
            ->filter(function ($service) use ($categoryTitle) {
                $normalized = $this->normalizeServiceCategory(
                    $service->category ?? null,
                    $service->bath_type ?? null,
                    $service->description ?? null
                );
                return $normalized === $categoryTitle;
            })
            ->values();

        // Category metadata
        $categoryMeta = [
            'Hot Stone Bath' => [
                'tone' => 'stone',
                'image' => '/image/hot%20stone%20bath.png',
                'subtitle' => 'Classic heated-stone wellness sessions',
            ],
            'Medicinal Water' => [
                'tone' => 'medicinal',
                'image' => '/image/medicinal water.png',
                'subtitle' => 'Therapeutic baths focused on recovery',
            ],
        ];

        $meta = $categoryMeta[$categoryTitle] ?? [];

        return view('web.services-by-category', compact('categoryTitle', 'services', 'categoryMeta', 'meta'));
    }

    public function serviceDetail(Service $service): View|RedirectResponse
    {
        // Check if service is available
        if ($service->status !== 'approved' || !$service->is_available) {
            return redirect()->route('home')->with('error', 'Service not found or unavailable.');
        }

        // Get related services from the same bath name in the service catalog.
        $relatedServices = Service::query()
            ->where('bath_name', $service->bath_name)
            ->where('id', '!=', $service->id)
            ->where('status', 'approved')
            ->where('is_available', true)
            ->limit(4)
            ->get();

        return view('web.service-detail', compact('service', 'relatedServices'));
    }

    /**
     * Show index of all services (searchable / paginated)
     */
    public function servicesIndex(Request $request): View
    {
        $query = Service::query()->where('status', 'approved')->where('is_available', true)->with('ownerBath');

        if ($request->filled('keyword')) {
            $keyword = trim((string) $request->input('keyword'));
            $query->where(function ($q) use ($keyword) {
                $q->where('service_type', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('bath_name', 'like', "%{$keyword}%");
            });
        }

        $services = $query->latest()->paginate(12)->appends(request()->query());

        return view('web.services', compact('services'));
    }

    /**
     * Show wellness baths / categories page
     */
    public function wellnessBaths(): View
    {
        $categories = ['Hot Stone Bath', 'Medicinal Water'];
        // featured baths: pick active baths with images
        $featured = Bath::query()->with('images')->where('status', 'active')->latest()->limit(8)->get();
        return view('web.wellness-baths', compact('categories', 'featured'));
    }

    /**
     * Contact us page
     */
    public function contact(): View
    {
        return view('web.contact');
    }

    public function submitContact(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        // For now: log contact and flash message. In future: send email to support.
        \Log::info('Contact form submitted', $data);

        return back()->with('success', 'Thanks — your message has been sent. We will reply soon.');
    }

    private function categoryAndBathTypeFromServiceType(?string $serviceType): array
    {
        $type = strtolower(trim((string) $serviceType));

        $explicitMap = [
            'natural hot spring water bath' => ['category' => 'Hot Spring', 'bath_type' => 'tshachu'],
            'medicinal water bath' => ['category' => 'Medicinal Water', 'bath_type' => 'dotsho'],
            'traditional hotstone bath' => ['category' => 'Hot Stone Bath', 'bath_type' => 'menchu'],
            'herbal hotstone' => ['category' => 'Hot Stone Bath', 'bath_type' => 'menchu'],
            'oil bath' => ['category' => 'Hot Stone Bath', 'bath_type' => 'menchu'],
            'herbal steam / wellness bath' => ['category' => 'Hot Stone Bath', 'bath_type' => 'menchu'],
            'foot bath' => ['category' => 'Hot Stone Bath', 'bath_type' => 'menchu'],
            'relaxing hot stone bath' => ['category' => 'Hot Stone Bath', 'bath_type' => 'menchu'],
            'detox steam bath' => ['category' => 'Hot Stone Bath', 'bath_type' => 'menchu'],
        ];

        if (isset($explicitMap[$type])) {
            return $explicitMap[$type];
        }

        if (str_contains($type, 'spring') || str_contains($type, 'tshachu')) {
            return ['category' => 'Hot Spring', 'bath_type' => 'tshachu'];
        }

        if (str_contains($type, 'medicinal') || str_contains($type, 'mineral') || str_contains($type, 'dotsho')) {
            return ['category' => 'Medicinal Water', 'bath_type' => 'dotsho'];
        }

        return ['category' => 'Hot Stone Bath', 'bath_type' => 'menchu'];
    }

    private function normalizeServiceCategory(?string $category, ?string $bathType, ?string $serviceType): string
    {
        $allowed = ['Hot Stone Bath', 'Hot Spring Bath', 'Medicinal Water', 'Spa & Massage Therapy', 'Herbal Steam Bath', 'Yoga & Meditation'];

        if (in_array((string) $category, $allowed, true)) {
            return (string) $category;
        }

        if ($bathType === 'tshachu') {
            return 'Hot Spring Bath';
        }

        if ($bathType === 'dotsho') {
            return 'Medicinal Water';
        }

        return $this->categoryAndBathTypeFromServiceType($serviceType)['category'];
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

        $address = $validated['address'] ?? null;

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
            \App\Services\MailService::send($user->email, new \App\Mail\UserRegistrationConfirmationMail($user));
        } catch (\Exception $e) {
            Log::error('Failed to send user confirmation email: ' . $e->getMessage());
        }

        // Send notification to all admins about new user registration (ensure primary admin gets it)
        try {
            $adminEmails = User::where('role', 'admin')->whereNotNull('email')->pluck('email')->filter()->toArray();
            $fallback = env('ADMIN_EMAIL') ?: config('mail.from.address');
            if (! empty($fallback)) {
                $adminEmails[] = $fallback;
            }
            // Ensure primary admin receives notifications
            $adminEmails[] = 'chungkutshomo@gmail.com';
            $adminEmails = array_values(array_unique(array_filter($adminEmails)));
            if (! empty($adminEmails)) {
                foreach ($adminEmails as $email) {
                    try {
                        \App\Services\MailService::send($email, new \App\Mail\NewUserRegistrationMail($user));
                    } catch (\Exception $e) {
                        Log::error('Failed to send admin notification to ' . $email . ': ' . $e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to prepare admin notification: ' . $e->getMessage());
        }

        Auth::login($user);

        return redirect()->route('services.by.category', 'hot-stone-bath')->with('success', '✅ Registration successful! Browse services and start booking.');
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

        return redirect()->route('services.by.category', 'hot-stone-bath')->with('success', '✅ Login successful. Browse services and start booking!');
    }

    public function dashboard(): View|RedirectResponse
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'guest') {
            return redirect()->route('guest.login');
        }

        $bookings = Booking::query()
            ->with(['bath', 'service', 'review'])
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
            'images',
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

            $sessionPrice = (float) ($bath->final_price ?? $bath->price_per_session ?? $service->price ?? $bath->price_per_hour ?? 0);
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

            // Commission calculation and escrow flow for digital payments
            $commissionPercent = (float) config('app.platform_commission', 20);
            $adminCommission = round($totalPrice * $commissionPercent / 100, 2);
            $totalCharged = round($totalPrice + $adminCommission, 2);

            // Create transaction record for ALL bookings (both digital and cash)
            $transaction = \App\Models\Transaction::create([
                'transaction_id' => 'TXN' . now()->format('YmdHis') . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT),
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'payment_method' => $validated['payment_method'] === 'digital' ? ($validated['banking_app'] ?? 'digital') : 'cash',
                'amount' => $validated['payment_method'] === 'digital' ? $totalCharged : $totalPrice,
                'status' => $validated['payment_method'] === 'digital' ? 'success' : 'pending',
                'processed_at' => $validated['payment_method'] === 'digital' ? now() : null,
            ]);

            // If payment is digital and successful, create escrow transaction (held)
            if ($validated['payment_method'] === 'digital') {
                try {
                    \App\Models\EscrowTransaction::create([
                        'booking_id' => $booking->id,
                        'customer_id' => $user->id,
                        'owner_id' => $bath->owner->id ?? null,
                        'total_amount' => $totalCharged,
                        'owner_amount' => $totalPrice,
                        'admin_commission' => $adminCommission,
                        'escrow_status' => 'held',
                        'payment_method' => $transaction->payment_method,
                        'transaction_reference' => $transaction->transaction_id,
                    ]);
                } catch (\Throwable $e) {
                    Log::error('Failed to create escrow transaction: ' . $e->getMessage());
                }
                // Notify admins and owner about new escrow
                try {
                    $tx = \App\Models\EscrowTransaction::where('booking_id', $booking->id)->latest()->first();
                    if ($tx) {
                        $admins = \App\Models\User::where('role', 'admin')->get();
                        if ($admins->isNotEmpty()) {
                            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewEscrowPayment($tx));
                        }
                        // notify owner too
                        if ($bath->owner) {
                            $bath->owner->notify(new \App\Notifications\NewEscrowPayment($tx));
                        }
                    }
                } catch (\Throwable $e) {
                    Log::error('Failed sending escrow notifications: ' . $e->getMessage());
                }
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
                    Log::error('Failed to send booking notification email: ' . $e->getMessage());
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
            Log::error('Booking error: ' . $e->getMessage());
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

        $booking->load(['bath', 'service', 'review']);

        return view('web.guest.booking-summary', compact('booking'));
    }

    public function submitReview(Request $request, Booking $booking): RedirectResponse
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'guest' || $booking->guest_id !== $user->id) {
            return redirect()->route('guest.login');
        }

        if ($booking->status !== 'completed') {
            return back()->with('error', 'You can only review completed bookings.');
        }

        if ($booking->review()->exists()) {
            return back()->with('error', 'You have already submitted a review for this booking.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $review = Review::create([
            'booking_id' => $booking->id,
            'guest_id' => $user->id,
            'bath_id' => $booking->bath_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        $ownerId = optional($booking->bath)->owner_id;
        if ($ownerId) {
            Message::create([
                'booking_id' => $booking->id,
                'sender_id' => $user->id,
                'recipient_id' => $ownerId,
                'message' => 'New review: ' . $user->name . ' rated your service ' . $review->rating . '/5 for booking #' . $booking->booking_id . '.',
                'is_read' => false,
            ]);
        }

        return redirect()
            ->route('guest.booking.summary', $booking)
            ->with('success', 'Thank you! Your rating and review were submitted successfully.');
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

        return redirect()->route('guest.login');
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

        $bath->load(['availabilities']);

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
        $estimatedPrice = $validated['number_of_persons'] * ((float) ($bath->final_price ?? $bath->price_per_session ?? $bath->price_per_hour ?? 0));

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
