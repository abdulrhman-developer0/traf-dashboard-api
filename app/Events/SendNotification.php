<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, array $data = [])
    {
        $this->user = $user;
        $this->data = $data;
    }

    public function broadcastAs()
    {
        return 'SendNotification';
    }


    public function broadcastOn(): array
    {
        /************ IMPORTANT */
        // The ID added here related to the user ID

        return [
            new PrivateChannel("notifications.{$this->user->id}"),
        ];
    }
    // getdata for pusher 
    public function broadcastWith()
    {
        return $this->data;
    }
}