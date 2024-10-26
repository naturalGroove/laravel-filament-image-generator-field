# Image Generator Form Field for Laravel Filament

[![Latest Version on Packagist](https://img.shields.io/packagist/v/naturalGroove/laravel-filament-image-generator-field.svg?style=flat-square)](https://packagist.org/packages/naturalGroove/laravel-filament-image-generator-field)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/naturalGroove/laravel-filament-image-generator-field/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/naturalGroove/laravel-filament-image-generator-field/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/naturalGroove/laravel-filament-image-generator-field/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/naturalGroove/laravel-filament-image-generator-field/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/naturalGroove/laravel-filament-image-generator-field.svg?style=flat-square)](https://packagist.org/packages/naturalGroove/laravel-filament-image-generator-field)

This custom field allows you to generate images with different sizes and formats using AI models like OpenAI DALL-E.
It extends the FileUpload field and adds a button to open the image generator modal where you can set the sizes and formats of the generated images.

<img src="https://netseven.dev/filament-image-generator-field/screenshots/plugin-showcase.webp" alt="filament image generator ai" width="1920" height="auto" class="filament-hidden" style="width: 100%;" />

# Installation

Before you begin, you must have the Laravel Filament package installed and configured. If you haven't done this yet, you can find the installation instructions [here](https://filamentadmin.com/docs/installation).

## Prerequisites

Default Image Generator is set to OpenAI DALL-E (version 3). You should have an API key to use it. You can get it [here](https://platform.openai.com/).
After You get the API key, you should set it in your .env file:

```bash
OPEN_AI_DALL_E_API_KEY=your-api-key
```

## Install the package via composer

Run the following command in your terminal to install the package:

```bash
composer require naturalGroove/laravel-filament-image-generator-field
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-image-generator-field-config"
```

Configuration file lets you set the default image generator and the available image generators for the field.

Optionally, you can publish the views to customize the field:

```bash
php artisan vendor:publish --tag="filament-image-generator-field-views"
```

## Usage

![screenshot](https://netseven.dev/filament-image-generator-field/screenshots/plugin-screencast.png)

Just add new Field or replace your FileUpload field with ImageGenerator field in your form schema definition:

```php
use \NaturalGroove\Filament\ImageGeneratorField\Forms\Components\ImageGenerator;

[...]
public static function form(Form $form): Form
{
    return $form
        ->schema([
            ImageGenerator::make('photo'),
        ]);
```

If You are replacing the FileUpload field:

```diff
use \NaturalGroove\Filament\ImageGeneratorField\Forms\Components\ImageGenerator;

    [...]
-   FileUpload::make('photo'),
+   ImageGenerator::make('photo'),
```

You could use all the same options as FileUpload field, for example:

```php
use \NaturalGroove\Filament\ImageGeneratorField\Forms\Components\ImageGenerator;

ImageGenerator::make('photo')
    ->imageEditor()
    ->disk('private'),
```

This plugin comes with a default image generator set to OpenAI DALL-E.
You can select which version of the model you want to use when defining the field:

```php
ImageGenerator::make('photo'
    ->imageGenerator('openai-dall-e-3'),
```

There are predefined shortcuts for the image generators:

```php
ImageGenerator::make('photo')
    ->openaiDallE2(); // equivalent to ->imageGenerator('openai-dall-e-2')

ImageGenerator::make('photo')
    ->openaiDallE3(); // equivalent to ->imageGenerator('openai-dall-e-3')
```

Depending on the image generator you choose, there are different options you can set. For example Dall-E 2 allows to set the number of images generated.

After you add the field to your form, you should see a button next to the file input. When you click the button, the image generator modal will open.

![screenshot](https://netseven.dev/filament-image-generator-field/screenshots/field-with-button.webp)

## Adding custom image generators

You can add custom image generators by impolemeting the `NaturalGroove\Filament\ImageGeneratorField\Contracts\AIImageGenerator` interface.
Your class should have the all the methods from the interface and should be registered in the config file.
Format of returned array should be the same as in the example below:

```php
use NaturalGroove\Filament\ImageGeneratorField\Contracts\AIImageGenerator;

class MyCustomImageGenerator implements AIImageGenerator
{
    public function generate(string $prompt, int $n = 1, array $params = []): array
    {
        // your implementation
        
        return [
            [
                'url' => 'https://example.com/image.jpg'
            ]
            [...]
        ];
    }

}
```

![screenshot](https://netseven.dev/filament-image-generator-field/screenshots/modal-with-show-all-options.webp)

## Upcoming features

- [ ] Add more image generators
- [ ] Add functionality to edit your uploaded image with AI models (img2img)
- [ ] Add more options to the field

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
