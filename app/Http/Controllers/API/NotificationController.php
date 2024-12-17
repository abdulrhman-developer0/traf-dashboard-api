<?php

namespace App\Http\Controllers\API;

use App\Events\SendNotification;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Notification;
use App\Notifications\DBNotification;
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

        // $bookings =  Booking::query()
        //     ->latest()
        //     ->where('date', '>=', now())
        //     // ->where('date', '<=', now()->endOfDay())
        //     ->get();

        // foreach ($bookings as $booking) {


        //     $hours = now()->diffInHours($booking->date);
            

        //     if ($hours > 0 && $hours <= 3) {
        //         $data = BookingResource::make($booking)->toArray(request());
        //         $user = $booking->client->user;

        //         // Notify the user (database + broadcast)
        //         $user->notify(new DBNotification($data));
        //         SendNotification::dispatch($user, $data);
        //     }
        // }


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
