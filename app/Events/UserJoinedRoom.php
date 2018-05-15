<?php

namespace App\Events;

use App\Database\Repositories\RoomRepository as Room;
use App\Database\Models\User;
use App\Helpers\RouteHelper;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserJoinedRoom implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The room the user is joining
     *
     * @var App\Database\Repositories\RoomRepository
     */
    public $room;

    /**
     * The user joining the room
     *
     * @var App\Database\Models\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param App\Database\Repositories\RoomRepository   $room   the room the user is joining
     * @param App\Database\Models\User   $user   the user joining the room
     * @return void
     */
    public function __construct(Room $room, User $user)
    {
        $this->room = $room;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('room.' . $this->room->getKey());
    }
}
