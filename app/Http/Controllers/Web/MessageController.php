<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    /**
     * Show messages for a specific booking
     */
    public function showMessages(Booking $booking): View
    {
        $user = Auth::user();
        
        // Check authorization
        if ($user->role === 'admin') {
            // Admin can view any booking's messages
        } elseif ($user->role === 'guest' && $booking->guest_id !== $user->id) {
            abort(403, 'Unauthorized');
        } elseif ($user->role === 'owner' && $booking->bath->owner_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Load messages for this booking
        $messages = Message::where('booking_id', $booking->id)
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        Message::where('booking_id', $booking->id)
            ->where('recipient_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // Get other party info for display
        if ($user->role === 'admin') {
            $otherParty = $booking->guest;
        } else {
            $otherParty = $user->role === 'guest' ? 
                User::findOrFail($booking->bath->owner_id) : 
                $booking->guest;
        }

        return view('web.admin.messages', compact('booking', 'messages', 'otherParty'));
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request, Booking $booking): JsonResponse
    {
        $user = Auth::user();

        // Check authorization
        if ($user->role === 'admin') {
            // Admin can message anyone
            $recipientId = $booking->guest_id;
        } elseif ($user->role === 'guest' && $booking->guest_id === $user->id) {
            // Guest can message owner
            $recipientId = $booking->bath->owner_id;
        } elseif ($user->role === 'owner' && $booking->bath->owner_id === $user->id) {
            // Owner can message guest
            $recipientId = $booking->guest_id;
        } else {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $message = Message::create([
            'booking_id' => $booking->id,
            'sender_id' => $user->id,
            'recipient_id' => $recipientId,
            'message' => $validated['message'],
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
            'sender' => $message->sender,
        ]);
    }

    /**
     * Get unread message count for admin
     */
    public function getUnreadCount(): JsonResponse
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['count' => 0]);
        }

        $count = Message::where('recipient_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get pending booking requests with unread messages
     */
    public function getPendingRequests()
    {
        try {
            $user = Auth::user();

            if (!$user || $user->role !== 'admin') {
                return response()->json([], 403);
            }

            $bookings = Booking::where('status', 'pending')
                ->with([
                    'guest:id,name,email,phone',
                    'bath:id,name,owner_id',
                    'messages' => function ($query) {
                        $query->where('is_read', false)
                            ->where('recipient_id', Auth::id())
                            ->orderBy('created_at', 'desc');
                    }
                ])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->makeHidden(['updated_at'])
                ->toArray();

            return response()->json($bookings);
        } catch (\Exception $e) {
            \Log::error('getPendingRequests error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load pending requests'], 500);
        }
    }
}
