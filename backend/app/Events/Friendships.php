<?php

namespace App\Events;

use App\Models\Friendship;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Friendships implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $friendship;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Friendship $friendship)
    {
      $this->friendship = $friendship;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('friendship.' . $this->friendship->user_requested);
    }

    public function broadcastWith()
    {
        $this->friendship->load('fromUser');

        return ["friendship" => $this->friendship];
    }
}
