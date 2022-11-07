# This is my package metadata

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marshmallow/metadata.svg?style=flat-square)](https://packagist.org/packages/marshmallow/metadata)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/marshmallow/metadata/run-tests?label=tests)](https://github.com/marshmallow/metadata/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/marshmallow/metadata/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/marshmallow/metadata/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/marshmallow/metadata.svg?style=flat-square)](https://packagist.org/packages/marshmallow/metadata)

Add metadata to any model with a simple cast

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
    'metadata_model' => Marshmallow\Models\Metadata::class,

    /*
     * The fully qualified class name of the metadata cast.
     */
    'metadata_cast' => Marshmallow\Casts\MetadataCast::class,
];
```

## Usage

Add the following trait to your model

```php
use Marshmallow\Metadata\Traits\HasMetadata;

use HasMetadata;

```

Add add cast for your required field.

```php
use Marshmallow\Metadata\Casts\MetadataCast;

protected $casts = [
    'example' => MetadataCast::class,
];
```

After which you can get & set your field using normal methods;

```php
$example_model->example_field = 'This is an example';

$example_field = $example_model->example_field;
// 'This is an example'
```

The data from the field will be decoded & encoded to a json format.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Marshmallow](https://github.com/marshmallow-packages)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
