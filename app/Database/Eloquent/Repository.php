<?php

namespace App\Database\Eloquent;

use App\Database\Exceptions\RepositoryException;
use App\Database\Interfaces\RepositoryInterface;
use App\Database\Traits\HasAttributes;
use App\Database\Traits\HidesAttributes;
use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class Repository implements RepositoryInterface, Arrayable, Jsonable
{
    use HasAttributes,
        HidesAttributes;

    /**
     * The application container
     *
     * @var \Illuminate\Container\Container
     */
    private $app;

    /**
     * The location of models.
     *
     * @var string
     */
    private $modelDirectory = "App\Database\Models\\";

    /**
     * An instance of the model
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * The class constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->app = new Container();
        $this->makeModel();
    }

    /**
     * Triggered when invoking inaccessible methods in an object context.
     *
     * @param string    $method
     * @param array     $parameters
     * @return mixed
     */
    public function __call($method, $args)
    {
        // Call the intended method on the model.
        $result = call_user_func_array([$this->model, $method], $args);

        // If the data returned from the call is an instance of the model, set 
        // it and return the repository instance.
        if ($this->checkModel()) {
            $this->model = $result;
            return $this;
        }

        // TODO: Should throw an exception here

        return null;
    }

    /**
     * Triggered when invoking inaccessible (undefined) methods in a static context.
     *
     * @param string    $method
     * @param array     $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        // Call the method in an object context
        return (new static)->$method(...$parameters);
    }

    /**
     * Dynamically retrieve attributes on the repository or model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Convert the repository to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Return the model class name.
     *
     * @return string
     */
    abstract function modelName();

    /**
     * Verify that the model is currently an instance of the correct model class
     *
     * @param  Model    $model    (optional) model object to check
     * @return bool
     */
    public function checkModel($model = null)
    {
        $model = $model ?: $this->model;

        return is_object($model) && get_class($model) == $this->modelDirectory . $this->modelName();
    }

    /**
     * Setup the model used by the repository.
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \App\Database\Exceptions\RepositoryException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->modelDirectory . $this->modelName());
 
        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->modelDirectory}{$this->modelName()} must be an instance of \\Illuminate\\Database\\Eloquent\\Model");
        }
 
        return $this->model = $model;
    }

    /**
     * Convert the repository instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge($this->model->toArray(), $this->attributesToArray());
    }

    /**
     * Convert the model instance to JSON.
     *
     * @param  int  $options
     * @return string
     * @throws \Illuminate\Database\Eloquent\JsonEncodingException
     */
    public function toJson($options = 0)
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw JsonEncodingException::forModel($this, json_last_error_msg());
        }

        return $json;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}