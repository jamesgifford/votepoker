<?php

namespace App\Events;

use App\Database\Models\Topic;
use App\Database\Models\TopicUser;
use App\Helpers\RouteHelper;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TopicClosed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The new topic
     *
     * @var App\Database\Models\Topic
     */
    public $topic;

    /**
     * The results from voting on the topic
     *
     * @var array
     */
    public $results;

    /**
     * Create a new event instance.
     *
     * @param App\Database\Models\Topic   $topic   the new topic
     * @return void
     */
    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
        $this->results = [
            'votes' => $topic->votes
            'stats' => TopicUser::selectRaw('value, count(value) as count')->where('topic_id', '=', $topic->id)->groupBy('value')->get()
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('room.' . RouteHelper::encodeKey($this->topic->room_id));
    }
}
