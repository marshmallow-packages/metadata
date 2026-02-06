<?php

// config for Marshmallow/Metadata
return [
    /*
     * The fully qualified class name of the metadata model.
     */
    'metadata_model' => Marshmallow\Metadata\Models\Metadata::class,

    /*
     * The fully qualified class name of the metadata cast.
     */
    'metadata_cast' => Marshmallow\Metadata\Casts\MetadataCast::class,

    /*
     * Automatically eager load metadata to prevent N+1 queries.
     * Set to false if you prefer to manually eager load metadata.
     */
    'eager_load' => true,
];
