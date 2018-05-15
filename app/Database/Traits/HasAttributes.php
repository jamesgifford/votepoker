<?php

namespace App\Database\Traits;

use Illuminate\Support\Str;

trait HasAttributes
{
    /**
     * The repository's attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = [];

        if (isset($this->attributes)) {
            foreach ($this->getArrayableAttributes() as $attribute) {
                $attributes[$attribute] = $this->getAttribute($attribute);
            }
        }

        return $attributes;
    }

    /**
     * Get an attribute from the model.
     *
     * @param string  $attribute
     * @return mixed
     */
    public function getAttribute($attribute)
    {
        if (! $attribute) {
            return;
        }

        // Return the attribute value if it exists in the class attributes array
        if (in_array($attribute, $this->attributes)) {
            $attributeMethod = 'get' . Str::studly($attribute) . 'Attribute';

            return $this->{$attributeMethod}();
        }

        return;
    }

    /**
     * Get an attribute array of all arrayable values.
     *
     * @param  array  $values
     * @return array
     */
    public function getArrayableAttributes()
    {
        $values = $this->attributes;

        if (count($this->getVisible()) > 0) {
            $values = array_intersect($values, $this->getVisible());
        }

        if (count($this->getHidden()) > 0) {
            $values = array_diff($values, $this->getHidden());
        }

        return $values;
    }
}
