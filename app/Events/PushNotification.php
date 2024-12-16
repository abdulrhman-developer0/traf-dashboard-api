<?php 
 
namespace App\Events; 
 
use App\Models\Booking; 
use App\Models\User; 
use Illuminate\Broadcasting\Channel; 
use Illuminate\Broadcasting\InteractsWithSockets; 
use Illuminate\Broadcasting\PresenceChannel; 
use Illuminate\Broadcasting\PrivateChannel; 
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; 
use Illuminate\Foundation\Events\Dispatchable; 
use Illuminate\Queue\SerializesModels; 
 
class PushNotification 
{ 
    use Dispatchable, InteractsWithSockets, SerializesModels; 
 
    /** 
     * Create a new event instance. 
     */ 
    public function __construct( 
        public User $user, 
        public $data = null, 
    ) {} 
 
    public function via() 
    { 
        return ['database']; 
    } 
 
    public function broadcastOn() 
    { 
        return new Channel("notifications.{$this->user->id}"); // Or PrivateChannel if necessary 
    } 
 
    public function broadcastAs() 
    { 
        return 'notification'; 
    } 
 
    public function broadcastWith() 
    { 
        return $this->data ?? []; 
    } 
}