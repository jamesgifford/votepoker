<?php

namespace App\Database\Repositories;

use App\Database\Eloquent\Repository;
use App\Database\Models\User;
use App\Helpers\RouteHelper;

class RoomRepository extends Repository
{
    /**
     * Return the model class name.
     *
     * @return string
     */
    public function modelName()
    {
        return 'Room';
    }

    /**
     * Create a new room
     *
     * @var User    $user   the user creating the room
     * @return $this
     */
    public static function store(User $user)
    {
        return self::create([
            'user_id' => $user->id
        ]);
    }

    /**
     * Check if the specified user has joined the room.
     *
     * @param User  $user
     * @return bool
     */
    public function containsUser($user)
    {
        return $this->model->users->contains($user);
    }

    /**
     * Add the specified user to the room.
     *
     * @param User  $user
     * @return bool
     */
    public function addUser($user)
    {
        return $this->model->users()->save($user);
    }

    public function getKey()
    {
        return $this->model->key;
    }
}