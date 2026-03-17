<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Bath;
use App\Models\BathService;
use App\Models\BathFacility;
use App\Models\Availability;
use App\Models\BathImage;
use App\Models\Booking;
use Illuminate\Http\Request;

class ProviderDashboardController extends Controller
{
    /**
     * Get provider's bath details.
     */
    public function getBathDetails(Request $request)
    {
        $bath = Bath::where('owner_id', $request->user()->id)
            ->with(['services', 'facilities', 'images', 'availabilities', 'documents', 'bookings'])
            ->firstOrFail();

        return response()->json($bath);
    }

    /**
     * Update bath details.
     */
    public function updateBathDetails(Request $request)
    {
        $bath = Bath::where('owner_id', $request->user()->id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'short_description' => 'sometimes|string',
            'detailed_description' => 'sometimes|string',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'full_address' => 'sometimes|string',
            'max_guests' => 'sometimes|integer',
            'price_per_hour' => 'sometimes|numeric',
            'booking_type' => 'sometimes|in:instant,approval_required',
            'cancellation_policy' => 'sometimes|string',
        ]);

        $bath->update($validated);

        return response()->json([
            'message' => 'Bath details updated successfully',
            'bath' => $bath,
        ]);
    }

    /**
     * Add bath service.
     */
    public function addService(Request $request)
    {
        $bath = Bath::where('owner_id', $request->user()->id)->firstOrFail();

        $validated = $request->validate([
            'service_type' => 'required|string',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:15',
            'price' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
        ]);

        $service = BathService::create(array_merge($validated, [
            'bath_id' => $bath->id,
        ]));

        return response()->json([
            'message' => 'Service added successfully',
            'service' => $service,
        ], 201);
    }

    /**
     * Update bath service.
     */
    public function updateService(Request $request, $serviceId)
    {
        $service = BathService::findOrFail($serviceId);
        $bath = $service->bath;

        if ($bath->owner_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'service_type' => 'sometimes|string',
            'description' => 'nullable|string',
            'duration_minutes' => 'sometimes|integer|min:15',
            'price' => 'sometimes|numeric|min:0',
            'max_guests' => 'sometimes|integer|min:1',
            'is_available' => 'sometimes|boolean',
        ]);

        $service->update($validated);

        return response()->json([
            'message' => 'Service updated successfully',
            'service' => $service,
        ]);
    }

    /**
     * Delete bath service.
     */
    public function deleteService(Request $request, $serviceId)
    {
        $service = BathService::findOrFail($serviceId);
        $bath = $service->bath;

        if ($bath->owner_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $service->delete();

        return response()->json(['message' => 'Service deleted successfully']);
    }

    /**
     * Add facility.
     */
    public function addFacility(Request $request)
    {
        $bath = Bath::where('owner_id', $request->user()->id)->firstOrFail();

        $validated = $request->validate([
            'facility_name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $facility = BathFacility::create(array_merge($validated, [
            'bath_id' => $bath->id,
        ]));

        return response()->json([
            'message' => 'Facility added successfully',
            'facility' => $facility,
        ], 201);
    }

    /**
     * Update facility.
     */
    public function updateFacility(Request $request, $facilityId)
    {
        $facility = BathFacility::findOrFail($facilityId);

        if ($facility->bath->owner_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'facility_name' => 'sometimes|string',
            'description' => 'nullable|string',
            'is_available' => 'sometimes|boolean',
        ]);

        $facility->update($validated);

        return response()->json([
            'message' => 'Facility updated successfully',
            'facility' => $facility,
        ]);
    }

    /**
     * Delete facility.
     */
    public function deleteFacility(Request $request, $facilityId)
    {
        $facility = BathFacility::findOrFail($facilityId);

        if ($facility->bath->owner_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $facility->delete();

        return response()->json(['message' => 'Facility deleted successfully']);
    }

    /**
     * Set availability schedule.
     */
    public function setAvailability(Request $request)
    {
        $bath = Bath::where('owner_id', $request->user()->id)->firstOrFail();

        $validated = $request->validate([
            'availabilities' => 'required|array',
            'availabilities.*.day_of_week' => 'required|integer|between:0,6',
            'availabilities.*.opening_time' => 'required|date_format:H:i',
            'availabilities.*.closing_time' => 'required|date_format:H:i',
            'availabilities.*.is_open' => 'required|boolean',
        ]);

        // Delete existing availabilities
        Availability::where('bath_id', $bath->id)->delete();

        // Create new availabilities
        foreach ($validated['availabilities'] as $availability) {
            Availability::create(array_merge($availability, [
                'bath_id' => $bath->id,
            ]));
        }

        return response()->json([
            'message' => 'Availability schedule set successfully',
            'availabilities' => Availability::where('bath_id', $bath->id)->get(),
        ]);
    }

    /**
     * Upload bath images.
     */
    public function uploadImages(Request $request)
    {
        $bath = Bath::where('owner_id', $request->user()->id)->firstOrFail();

        $validated = $request->validate([
            'images' => 'required|array',
            'images.*.image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'images.*.image_type' => 'required|in:bath_area,stones,seating,exterior,facilities',
            'images.*.description' => 'nullable|string',
        ]);

        $images = [];
        foreach ($validated['images'] as $key => $imageData) {
            $path = $request->file("images.$key.image")->store('bath_images', 'public');
            
            $image = BathImage::create([
                'bath_id' => $bath->id,
                'image_path' => $path,
                'image_type' => $imageData['image_type'],
                'description' => $imageData['description'] ?? null,
                'order' => $key,
            ]);

            $images[] = $image;
        }

        return response()->json([
            'message' => 'Images uploaded successfully',
            'images' => $images,
        ], 201);
    }

    /**
     * Delete bath image.
     */
    public function deleteImage(Request $request, $imageId)
    {
        $image = BathImage::findOrFail($imageId);

        if ($image->bath->owner_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $image->delete();

        return response()->json(['message' => 'Image deleted successfully']);
    }

    /**
     * Publish bath.
     */
    public function publishBath(Request $request)
    {
        $bath = Bath::where('owner_id', $request->user()->id)->firstOrFail();

        // Check if bath has all required information
        if (!$bath->services()->exists()) {
            return response()->json(['error' => 'Please add at least one service before publishing'], 400);
        }

        if (!$bath->availabilities()->exists()) {
            return response()->json(['error' => 'Please set your availability schedule before publishing'], 400);
        }

        if (!$bath->images()->exists()) {
            return response()->json(['error' => 'Please upload at least one image before publishing'], 400);
        }

        return response()->json([
            'message' => 'Bath is ready to be published',
            'bath' => $bath,
        ]);
    }

    /**
     * Get provider's dashboard statistics.
     */
    public function getDashboardStats(Request $request)
    {
        $bath = Bath::where('owner_id', $request->user()->id)->firstOrFail();

        $totalBookings = Booking::where('bath_id', $bath->id)->count();
        $confirmedBookings = Booking::where('bath_id', $bath->id)
            ->where('status', 'confirmed')
            ->count();
        $completedBookings = Booking::where('bath_id', $bath->id)
            ->where('status', 'completed')
            ->count();
        $cancelledBookings = Booking::where('bath_id', $bath->id)
            ->where('status', 'cancelled')
            ->count();

        $totalRevenue = Booking::where('bath_id', $bath->id)
            ->where('payment_status', 'paid')
            ->sum('total_price');

        return response()->json([
            'total_bookings' => $totalBookings,
            'confirmed_bookings' => $confirmedBookings,
            'completed_bookings' => $completedBookings,
            'cancelled_bookings' => $cancelledBookings,
            'total_revenue' => $totalRevenue,
        ]);
    }
}
