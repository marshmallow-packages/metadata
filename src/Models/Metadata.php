<?php

namespace Marshmallow\Metadata\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Metadata extends Model
{
    protected $casts = [
        'data' => AsCollection::class,
    ];

    protected $guarded = [];

    public function metadatable()
    {
        return $this->morphTo();
    }

    protected function getDataArray(): array
    {
        if (!filled($this->data)) {
            return [];
        }

        if (is_array($this->data)) {
            return $this->data;
        }

        return json_decode($this->data, true);
    }

    public function addMetadata($key, $value): void
    {
        $current_data = $this->getDataArray();
        $new_data = array_merge($current_data, [$key => $value]);

        if (!filled($value)) {
            $new_data = Arr::except($new_data, $key);
        }

        $this->update([
            'data' => $new_data,
        ]);
    }

    public function getMetadata(string $key)
    {
        $data = $this->getDataArray();
        if (!array_key_exists($key, $data)) {
            return null;
        }

        return $data[$key];
    }

    public function removeKey($key): void
    {
        $this->addMetadata($key, null);
    }

    public function clear(): void
    {
        $this->update([
            'data' => [],
        ]);
    }
}
