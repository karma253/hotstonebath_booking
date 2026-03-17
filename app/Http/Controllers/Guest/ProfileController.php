<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Get guest profile.
     */
    public function getProfile(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Update guest profile.
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
            'phone' => 'sometimes|string',
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }

    /**
     * Submit a review for completed booking.
     */
    public function submitReview(Request $request, $bookingId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $booking = $request->user()->bookings()
            ->where('bookings.id', $bookingId)
            ->where('status', 'completed')
            ->firstOrFail();

        // Check if review already exists
        if ($booking->review) {
            return response()->json(['error' => 'Review already submitted for this booking'], 400);
        }

        $review = Review::create([
            'booking_id' => $booking->id,
            'guest_id' => $request->user()->id,
            'bath_id' => $booking->bath_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return response()->json([
            'message' => 'Review submitted successfully',
            'review' => $review,
        ], 201);
    }

    /**
     * Get guest's reviews.
     */
    public function getMyReviews(Request $request)
    {
        $reviews = Review::where('guest_id', $request->user()->id)
            ->with(['bath', 'booking'])
            ->latest()
            ->paginate(20);

        return response()->json($reviews);
    }
}
