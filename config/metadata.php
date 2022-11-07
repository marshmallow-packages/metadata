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
];
