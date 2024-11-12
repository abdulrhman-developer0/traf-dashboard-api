<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $messageId;
    public $chatId;
    /**
     * Create a new event instance.
     */
    public function __construct($messageId,$chatId)
    {
        //
        $this->messageId=$messageId;
        $this->chatId=$chatId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
      
            return new PrivateChannel('chat.' . $this->chatId);

    }
    public function broadcastAs(){
        return 'message.delete';
    }
}
