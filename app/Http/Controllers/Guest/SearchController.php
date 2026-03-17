<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Bath;
use App\Models\Booking;
use App\Models\Dzongkhag;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Get all dzongkhags for search filter.
     */
    public function getDzongkhags()
    {
        $dzongkhags = Dzongkhag::all();
        return response()->json($dzongkhags);
    }

    /**
     * Search hot stone baths.
     */
    public function searchBaths(Request $request)
    {
        $validated = $request->validate([
            'dzongkhag_id' => 'nullable|exists:dzongkhags,id',
            'booking_date' => 'nullable|date|after_or_equal:today',
            'number_of_guests' => 'nullable|integer|min:1',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|gt:min_price',
            'search' => 'nullable|string',
            'sort_by' => 'nullable|in:price_asc,price_desc,rating,newest',
            'page' => 'nullable|integer|min:1',
        ]);

        $query = Bath::where('status', 'active')
            ->with(['services', 'facilities', 'images', 'dzongkhag', 'owner'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        // Filter by dzongkhag
        if ($request->has('dzongkhag_id') && $validated['dzongkhag_id']) {
            $query->where('dzongkhag_id', $validated['dzongkhag_id']);
        }

        // Filter by number of guests
        if ($request->has('number_of_guests') && $validated['number_of_guests']) {
            $query->where('max_guests', '>=', $validated['number_of_guests']);
        }

        // Filter by price range
        if ($request->has('min_price') && $validated['min_price']) {
            $query->where('price_per_hour', '>=', $validated['min_price']);
        }

        if ($request->has('max_price') && $validated['max_price']) {
            $query->where('price_per_hour', '<=', $validated['max_price']);
        }

        // Search by name or description
        if ($request->has('search') && $validated['search']) {
            $search = $validated['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('short_description', 'like', "%$search%")
                    ->orWhere('detailed_description', 'like', "%$search%");
            });
        }

        // Sort results
        $sortBy = $validated['sort_by'] ?? 'newest';
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price_per_hour', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_per_hour', 'desc');
                break;
            case 'rating':
                $query->orderByDesc('reviews_avg_rating');
                break;
            default:
                $query->latest('created_at');
        }

        $baths = $query->paginate($request->input('per_page', 12));

        return response()->json($baths);
    }

    /**
     * Get bath details with all information.
     */
    public function getBathDetails($bathId)
    {
        $bath = Bath::where('status', 'active')
            ->with([
                'services',
                'facilities',
                'images',
                'availabilities',
                'dzongkhag',
                'owner' => function ($query) {
                    $query->select('id', 'name', 'phone', 'email');
                },
                'reviews' => function ($query) {
                    $query->latest()->limit(10);
                }
            ])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->findOrFail($bathId);

        return response()->json($bath);
    }

    /**
     * Get available time slots for a specific bath and date.
     */
    public function getAvailableSlots(Request $request, $bathId)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'service_id' => 'required|exists:bath_services,id',
        ]);

        $bath = Bath::findOrFail($bathId);
        $dayOfWeek = \Carbon\Carbon::parse($validated['date'])->dayOfWeek;

        // Get bath availability for this day
        $availability = $bath->availabilities()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_open', true)
            ->first();

        if (!$availability) {
            return response()->json(['message' => 'Bath is closed on this day'], 400);
        }

        // Get already booked slots
        $bookedSlots = Booking::where('bath_id', $bathId)
            ->where('booking_date', $validated['date'])
            ->where('status', '!=', 'cancelled')
            ->get(['start_time', 'end_time']);

        // Generate available time slots (30-minute intervals)
        $slots = [];
        $startTime = \Carbon\Carbon::parse($availability->opening_time);
        $endTime = \Carbon\Carbon::parse($availability->closing_time);

        while ($startTime < $endTime) {
            $slotEnd = $startTime->copy()->addMinutes(30);
            
            if ($slotEnd > $endTime) {
                break;
            }

            // Check if this slot is available
            $isAvailable = !$bookedSlots->some(function ($booking) use ($startTime, $slotEnd) {
                $bookStart = \Carbon\Carbon::parse($booking->start_time);
                $bookEnd = \Carbon\Carbon::parse($booking->end_time);
                
                return !($slotEnd <= $bookStart || $startTime >= $bookEnd);
            });

            if ($isAvailable) {
                $slots[] = [
                    'start_time' => $startTime->format('H:i'),
                    'end_time' => $slotEnd->format('H:i'),
                ];
            }

            $startTime->addMinutes(30);
        }

        return response()->json([
            'date' => $validated['date'],
            'available_slots' => $slots,
        ]);
    }
}
