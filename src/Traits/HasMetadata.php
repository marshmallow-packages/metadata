<?php

namespace Marshmallow\Metadata\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Marshmallow\Casts\MetadataCast;
use Marshmallow\Metadata\Models\Metadata;

trait HasMetadata
{
    protected array $queuedMetadata = [];

    public static function getMetadataClassName(): string
    {
        return config('metadata.metadata_model', Metadata::class);
    }

    public static function getMetadataCastClassName(): string
    {
        return config('metadata.metadata_cast', MetadataCast::class);
    }

    public static function bootHasMetadata()
    {
        static::created(function (Model $metadatableModel) {
            if (count($metadatableModel->queuedMetadata) === 0) {
                return;
            }

            collect($metadatableModel->queuedMetadata)->each(function ($value, $key) use ($metadatableModel) {
                $metadatableModel->setMetadata($key, $value);
            });

            $metadatableModel->queuedMetadata = [];
        });

        static::deleted(function (Model $deletedModel) {
            $deletedModel->metadata()->delete();
        });
    }

    public function metadata(): MorphOne
    {
        return $this->morphOne(self::getMetadataClassName(), 'metadatable');
    }

    public function getMetadataCasts(): array
    {
        return collect($this->casts)
            ->filter(fn ($cast) => $cast == self::getMetadataCastClassName())
            ->map(fn ($cast, $key) => $key)
            ->toArray();
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->getMetadataCasts())) {
            return $this->setMetadata($key, $value);
        }

        parent::setAttribute($key, $value);
    }

    public function setMetadata($key, $value)
    {
        $encoded_value = $this->maybeEncodeMetadataValue($value);

        if (! $this->exists) {
            $this->queuedMetadata = [$key => $encoded_value];

            return;
        }

        $metadata = $this->metadata()->firstOrCreate([
            'metadatable_id' => $this->id,
            'metadatable_type' => get_class($this),
        ]);

        $metadata->addMetadata($key, $encoded_value);

        return $this;
    }

    public function getMetadata($key)
    {
        if (! in_array($key, $this->getMetadataCasts())) {
            return null;
        }

        if (empty($this->metadata)) {
            return null;
        }

        $value = $this->metadata->getMetadata($key);

        return $this->maybeDecodeMetadataValue($value);
    }

    protected function maybeDecodeMetadataValue($value)
    {
        if (empty($value)) {
            return null;
        }

        $object = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $object;
        }

        return $value;
    }

    protected function maybeEncodeMetadataValue($value)
    {
        if (is_object($value) || is_array($value)) {
            return json_encode($value, true);
        }

        return $value;
    }
}
