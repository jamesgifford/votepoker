<?php

namespace App\Database\Models;

use App\Helpers\RouteHelper;
use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title'
    ];

    /**
     * The attributes included from the model's JSON form.
     *
     * @var array
     */
    protected $visible = [
        'key',
        'title',
        'choices'
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
     * The hashed id value.
     *
     * @return string
     */
    public function getKeyAttribute()
    {
        return RouteHelper::encodeKey($this->id);
    }

    /**
     * Decode the choices attribute.
     *
     * @param string    $value  the value from the database
     * @return array
     */
    public function getChoicesAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Encode the choices attribute.
     *
     * @param array     $value  the value going into the database
     * @return void
     */
    public function setChoicesAttribute($value)
    {
        $this->attributes['choices'] = json_encode($value);
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
     * The topics that use the format.
     */
    public function topics()
    {
        return $this->hasMany('App\Database\Models\Topic')->withTimestamps();
    }
}
