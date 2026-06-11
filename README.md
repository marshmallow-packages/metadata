![alt text](https://marshmallow.dev/cdn/media/logo-red-237x46.png "marshmallow.")

# Metadata

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marshmallow/metadata.svg?style=flat-square)](https://packagist.org/packages/marshmallow/metadata)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/marshmallow-packages/metadata/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/marshmallow-packages/metadata/actions/workflows/fix-php-code-style-issues.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/marshmallow/metadata.svg?style=flat-square)](https://packagist.org/packages/marshmallow/metadata)

A package to simply add metadata to any Eloquent model with a simple cast. Metadata is stored in a single related table as JSON, keyed per model, so you can attach arbitrary attributes to a model without adding extra columns.

## Installation

You can install the package via composer:

```bash
composer require marshmallow/metadata
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="metadata-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="metadata-config"
```

This is the contents of the published config file:

```php
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
    'eager_load' => false,
];
```

## Configuration

| Key | Default | Description |
| --- | --- | --- |
| `metadata_model` | `Marshmallow\Metadata\Models\Metadata::class` | The model used to store metadata. Swap it to use your own. |
| `metadata_cast` | `Marshmallow\Metadata\Casts\MetadataCast::class` | The cast that maps a model attribute to metadata storage. |
| `eager_load` | `false` | When `true`, the `metadata` relation is automatically eager loaded on models using the trait to prevent N+1 queries. |

## Usage

Add the `HasMetadata` trait to your model:

```php
use Marshmallow\Metadata\Traits\HasMetadata;

class Example extends Model
{
    use HasMetadata;
}
```

Add a cast for every field you want to store as metadata:

```php
use Marshmallow\Metadata\Casts\MetadataCast;

protected $casts = [
    'example_field' => MetadataCast::class,
];
```

After which you can get & set your field using normal model attribute access:

```php
$example_model->example_field = 'This is an example';
$example_model->save();

$example_field = $example_model->example_field;
// 'This is an example'
```

Array and object values are automatically encoded to JSON on write and decoded on read:

```php
$example_model->example_field = ['key' => 'value'];

$example_model->example_field;
// ['key' => 'value']
```

When you set metadata on a model that has not been persisted yet, the value is queued and written automatically once the model is created. Metadata is also automatically deleted when the model itself is deleted.

### Working with the metadata relation directly

Each model that uses the trait exposes a `metadata` morph-one relation backed by the `Metadata` model, which offers a few helpers:

```php
// Read or write a single key
$example_model->metadata->getMetadata('example_field');
$example_model->metadata->addMetadata('example_field', 'value');

// Remove a single key
$example_model->metadata->removeKey('example_field');

// Clear all metadata for the model
$example_model->metadata->clear();
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please report security vulnerabilities by email rather than via the public issue tracker.

## Credits

-   [Marshmallow](https://github.com/marshmallow-packages)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
