<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class PushNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $id;

    /**
     * Create a new event instance.
     */
    public function __construct($data = null)
    {
        $this->data = $data;
        $this->id = uniqid();  // Generate a unique ID or set it based on your logic
    }

    /**
     * Define the channels the event will broadcast on.
     */
    public function broadcastOn()
    {
        return new Channel('notifications');  // Broadcasting on the notifications channel
    }

    /**
     * Define the event's broadcast name.
     */
    public function broadcastAs()
    {
        return 'notification';  // Event name for the broadcast
    }

    /**
     * Define the data to broadcast.
     */
    public function broadcastWith()
    {
        return $this->data ?? [];
    }

    /**
     * Convert the notification data to an array for database storage.
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->data['message'] ?? 'Default message',  // Example data structure
            'data' => $this->data ?? [],
        ];
    }
}
