<?php

namespace App\Database\Models;

use App\Database\Models\User;
use App\Helpers\RouteHelper;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id'
    ];

    /**
     * The attributes included from the model's JSON form.
     *
     * @var array
     */
    protected $visible = [
        'key',
        'user_key',
        'users'
    ];

    /**
     * The custom attributes appended to the model.
     *
     * @var array
     */
    protected $appends = [
        'key',
        'user_key',
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
     * The hashed user_id value.
     *
     * @return string
     */
    public function getUserKeyAttribute()
    {
        return RouteHelper::encodeKey($this->user_id);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKey()
    {
        return RouteHelper::encodeKey($this->getKey());
    }

    /**
     * The users that have joined the room.
     */
    public function users()
    {
        return $this->belongsToMany('App\Database\Models\User')->as('member')->withTimestamps();
    }

    /**
     * The topics held within the room.
     */
    public function topics()
    {
        return $this->hasMany('App\Database\Models\Topic')->withTimestamps();
    }
}
