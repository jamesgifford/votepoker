<?php

namespace App\Database\Models;

use App\Helpers\RouteHelper;
use Laravel\Spark\User as SparkUser;

class User extends SparkUser
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email'
    ];

    /**
     * The attributes included from the model's JSON form.
     *
     * @var array
     */
    protected $visible = [
        'key',
        'name',
        'value'
    ];

    /**
     * The custom attributes appended to the model.
     *
     * @var array
     */
    protected $appends = [
        'key'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
        'uses_two_factor_auth' => 'boolean'
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
     * The rooms the user has joined.
     */
    public function rooms()
    {
        return $this->belongsToMany('App\Database\Models\Rooms')->as('members')->withTimestamps();
    }

    /**
     * The topics the user has voted in.
     */
    public function topics()
    {
        return $this->belongsToMany('App\Database\Models\Topic')->withPivot('value')->withTimestamps()->as('vote')->using('App\Database\Models\TopicUser');
    }
}
