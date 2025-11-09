<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
//qr login event
class QrLoginConfirmed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

public $sessionId;
    public $user;

    public function __construct($sessionId, User $user)
    {
        $this->sessionId = $sessionId;
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return new Channel('qr-login.' . $this->sessionId);
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->user->id,
            'name' => $this->user->nombres. ''. $this->user->apellidos,
            'email' => $this->user->email,
        ];
    }
}
