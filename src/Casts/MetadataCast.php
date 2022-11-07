<?php

namespace Marshmallow\Metadata\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class MetadataCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        return $model->getMetadata($key);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        if (! $model->exists) {
            $model->queuedMetadata = [$key => $value];

            return;
        }

        $model->setMetadata($key, $value);

        return $value;
    }
}
