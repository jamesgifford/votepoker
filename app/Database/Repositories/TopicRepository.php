<?php

namespace App\Database\Repositories;

use App\Database\Eloquent\Repository;

class TopicRepository extends Repository
{
    /**
     * Return the model class name.
     *
     * @return string
     */
    public function modelName()
    {
        return 'Topic';
    }
}