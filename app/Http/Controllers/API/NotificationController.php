<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Notifications\ReminderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Retrieve all notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $user->notifications()->delete();
        for ($i = 0; $i < 20; $i += 1) {
            $user->notify(new ReminderNotification);
        }

        return response()->json(['data' => $notifications], 200);
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
