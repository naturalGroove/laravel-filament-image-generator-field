# A Laravel Filament Image Generator Form Field that extends the FileUpload field for generating images using AI

[![Latest Version on Packagist](https://img.shields.io/packagist/v/naturalGroove/laravel-filament-image-generator-field.svg?style=flat-square)](https://packagist.org/packages/naturalGroove/laravel-filament-image-generator-field)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/naturalGroove/laravel-filament-image-generator-field/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/naturalGroove/laravel-filament-image-generator-field/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/naturalGroove/laravel-filament-image-generator-field/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/naturalGroove/laravel-filament-image-generator-field/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/naturalGroove/laravel-filament-image-generator-field.svg?style=flat-square)](https://packagist.org/packages/naturalGroove/laravel-filament-image-generator-field)

![screenshot](https://netseven.dev/filament-image-generator-field/screenshots/main-showcase.webp)

Generate images with ImageGenerator field for Filament. This custom field allows you to generate images with different sizes and formats from a single upload.
It extends the FileUpload field and adds a button to open the imege generator modal where you can set the sizes and formats of the generated images.

## Installation

You can install the package via composer:

```bash
composer require naturalGroove/laravel-filament-image-generator-field
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-filament-image-generator-field-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-filament-image-generator-field-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-filament-image-generator-field-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Prerequisites

Default Image Generator is set to OpenAI DALL-E. You should have an API key to use it. You can get it [here](https://platform.openai.com/).
After You get the API key, you should set it in your .env file:

```bash
OPEN_AI_DALL_E_API_KEY=your-api-key
```

## Usage

Just replace your FileUpload field with ImageGenerator field in your form:

```php
\NaturalGroove\Filament\ImageGeneratorField\Forms\Components\ImageGenerator::make('photo'),
```

You could use all the same options as FileUpload field, for example:

```php
\NaturalGroove\Filament\ImageGeneratorField\Forms\Components\ImageGenerator::make('photo')
    ->imageEditor()
    ->disk('private'),
```
![screenshot](https://netseven.dev/filament-image-generator-field/screenshots/field.webp)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Grzegorz Adamczyk](https://github.com/naturalGroove)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
