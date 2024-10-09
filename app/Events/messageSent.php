<?php

namespace App\Events;

use App\Models\User;
use App\Models\message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use function Laravel\Prompts\error;

class messageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * Create a new event instance.
     */
    public function __construct(protected int $chat_id, protected User $user, protected message $message)
    {
    }

    public function broadcastWith(): array
    {
        return [
            'User' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'message' => [
                "msg" => $this->message->message,
                "created_at" => $this->message->created_at
            ]
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('Chat'),
        ];
    }
}
