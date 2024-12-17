<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;


class PusherNotification extends Notification
{
    use Queueable;

    public $user;
    public $data;

    public function __construct($user, $data = null)
    {
        $this->user = $user;
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'data' => $this->data,
            'message' => 'Your booking has been updated.',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'user_id' => $this->user->id,
            'data' => $this->data,
            'message' => 'Your booking has been updated.',
        ]);
    }

    public function broadcastOn()
    {
        return new PrivateChannel("notifications.{$this->user->id}");
    }

    public function broadcastAs()
    {
        return 'booking.updated';
    }

    public function shouldBroadcastNow()
    {
        return true; // Immediate broadcast
    }
}
