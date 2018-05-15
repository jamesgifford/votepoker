<?php

namespace App\Database\Models;

use App\Database\Models\Format;
use App\Database\Models\Room;
use App\Database\Models\User;
use App\Helpers\RouteHelper;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'room_id',
        'format_id',
        'title',
        'description'
    ];

    /**
     * The attributes included from the model's JSON form.
     *
     * @var array
     */
    protected $visible = [
        'key',
        'room_key',
        'format_key',
        'format'
    ];

    /**
     * The custom attributes appended to the model.
     *
     * @var array
     */
    protected $appends = [
        'key',
        'room_key',
        'format_key',
        'votes',
        'counts'
    ];

    /**
     * The hashed id value.
     *
     * @return string
     */
    public function getKeyAttribute()
    {
        return RouteHelper::encodeKey($this->id);
    }

    /**
     * The hashed room_id value.
     *
     * @return string
     */
    public function getRoomKeyAttribute()
    {
        return RouteHelper::encodeKey($this->room_id);
    }

    /**
     * The hashed format_id value.
     *
     * @return string
     */
    public function getFormatKeyAttribute()
    {
        return RouteHelper::encodeKey($this->format_id);
    }

    /**
     * 
     */
    public function getVotesAttribute()
    {
        $votes = [];
        foreach ($this->users as $user) {
            $votes[RouteHelper::encodeKey($user->id)] = $user->vote->value;
        }
        return $votes;
    }

    /**
     * 
     */
    public function getCountsAttribute()
    {
        $counts = [];
        //$test = $this->users()->withPivot('value')->select('value', \DB::raw("count(value)"))->groupBy('value')->get();
        $test = $this->users()->withPivot('value')->withCount('value')->get();
        foreach ($test as $thing) {
            var_dump($thing->value_count);
            //$counts[$thing->value] = $thing->count;
        }
        //var_dump($counts);

        /*
        $counts = [];
        foreach ($this->format->choices as $choice) {
            $users = $this->users()->wherePivot('value', '=', $choice)->count();
            $counts[$choice] = $users;
        }
        return $counts;
        */
        exit('!!');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKey()
    {
        return $this->getKey();
    }

    /**
     * Get the room this topic belongs to.
     */
    public function room()
    {
        return $this->belongsTo('App\Database\Models\Room');
    }

    /**
     * The voting format of the topic.
     */
    public function format()
    {
        return $this->belongsTo('App\Database\Models\Format');
    }

    /**
     * The users that have voted on the topic.
     */
    public function users()
    {
        return $this->belongsToMany('App\Database\Models\User')->withPivot('value')->withTimestamps()->as('vote')->using('App\Database\Models\TopicUser');
    }
}
