<?php

namespace App\Events;

use App\Database\Models\Room;
use App\Database\Models\Topic;
use App\Helpers\RouteHelper;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TopicCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The new topic
     *
     * @var App\Database\Models\Topic
     */
    public $topic;

    /**
     * The format of the new topic
     *
     * @var App\Database\Models\Format
     */
    public $format;

    /**
     * The key of the room the topic belongs to
     *
     * @var string
     */
    public $roomKey;

    /**
     * Create a new event instance.
     *
     * @param App\Database\Models\Topic   $topic   the new topic
     * @param string                      $roomKey the room key
     * @return void
     */
    public function __construct(Topic $topic, string $roomKey)
    {
        $this->topic = $topic;
        $this->format = $topic->format;
        $this->roomKey = $roomKey;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('room.' . $this->roomKey);
    }
}
