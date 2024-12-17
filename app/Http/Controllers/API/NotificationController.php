<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Notifications\ReminderNotification;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    use APIResponses;

    /**
     * Retrieve all notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Retrieve all notifications for the user, ordered by the most recent first
        $notifications = $user->notifications()->latest()->get();

        // Return the notifications as a JSON response
        return $this->okResponse($notifications->map(fn($n) => $n->data), 'Notifications retrieved successfuly.');
    }



    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if (!$notification || $notification->user_id !== auth()->id()) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['message' => 'Notification marked as read'], 200);
    }
}
