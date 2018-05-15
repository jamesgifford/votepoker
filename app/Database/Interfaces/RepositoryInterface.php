<?php

namespace App\Database\Interfaces;

interface RepositoryInterface
{
    /**
     * Triggered when invoking inaccessible methods in an object context.
     *
     * @access public
     */
    public function __call($method, $args);

    /**
     * Triggered when invoking inaccessible methods in a static context.
     *
     * @access public
     */
    public static function __callStatic($method, $parameters);

    /**
     * Setup the model used by the repository.
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws RepositoryException
     */
    public function makeModel();
}
