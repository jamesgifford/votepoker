<?php

namespace App\Database\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TopicUser extends Pivot
{
    /**
     * 
     */
    public function getCountsForTopic(\App\Database\Models\Topic $topic)
    {
        return self::selectRaw('value, count(value) as count')->where('topic_id', '=', $topic->id)->groupBy('value')->get();
    }
}