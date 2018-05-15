<?php

namespace App\Http\Controllers\Core;

use App\Database\Models\Room;
use App\Database\Models\Topic;
use App\Events\TopicClosed;
use App\Events\TopicCreated;
use App\Helpers\RouteHelper;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Handles voting rooms
 */
class TopicController extends Controller
{
    /**
     * Store a new topic in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        if (!($roomKey = $request->input('roomKey')) || !($userKey = $request->input('userKey'))) {
            return;
        }

        $topic = new Topic;
        $topic->user_id = RouteHelper::decodeKey($userKey);
        $topic->room_id = RouteHelper::decodeKey($roomKey);
        $topic->format_id = 9;
        $topic->started_at = Carbon::now();
        $topic->save();

        if ($topic) {
            broadcast(new TopicCreated($topic, $roomKey));
        }
    }

    /**
     * Update an existing topic.
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Database\Models\Topic $topic
     * @param  string                     $action
     * @return Response
     */
    public function update(Request $request, Topic $topic, string $action = '')
    {
        $action = strtolower($action);

        if ($action == 'close') {
            $topic->stopped_at = Carbon::now();
            $topic->save();

            broadcast(new TopicClosed($topic));
        }
        else if ($action == 'vote') {
            if (!($userKey = $request->input('userKey')) || $request->input('value') === false) {
                return;
            }

            $value = $request->input('value');

            try {
                $topic->users()->attach(RouteHelper::decodeKey($userKey), ['value' => $value]);
            } catch (\Illuminate\Database\QueryException $e) {
                // TODO: Look specifically for the duplicate key error
                $topic->users()->updateExistingPivot(RouteHelper::decodeKey($userKey), ['value' => $value]);
            }

            //broadcast(new UserVoted($topic));
        }
    }
}
