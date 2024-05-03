<?php

namespace NaturalGroove\Filament\ImageGeneratorField;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Livewire\Livewire;
use NaturalGroove\Filament\ImageGeneratorField\Components\GenerateForm;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentImageGeneratorFieldServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-image-generator-field';

    public static string $viewNamespace = 'filament-image-generator-field';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews();

        $package->hasInstallCommand(function (InstallCommand $command) {
            $command
                ->startWith(function (InstallCommand $command) {
                    $command->info('Hello, and welcome to Filament ImageGeneratorField installation!');

                    if ($command->confirm('Would you like to publish the config file?', false)) {
                        $command->callSilent('vendor:publish', ['--tag' => 'filament-image-generator-field', '--force' => true]);
                    }

                    if ($command->confirm('Would you like to publish the translations?', false)) {
                        $command->callSilent('vendor:publish', ['--tag' => 'filament-image-generator-field', '--force' => true]);
                    }
                })
                ->publishAssets()
                ->endWith(function (InstallCommand $command) {
                    $command->info('Have a great day!');
                });
        });

        Livewire::component('filament-image-generator-field::generate-form', GenerateForm::class);

        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );
    }

    /**
     * @return array<\Filament\Support\Assets\Asset>>
     */
    protected function getAssets(): array
    {
        return [
            Css::make('backend', __DIR__ . '/../resources/dist/backend.css')->loadedOnRequest(),
            Js::make('backend', __DIR__ . '/../resources/dist/backend.js')->loadedOnRequest()

        ];
    }

    protected function getAssetPackageName(): string
    {
        return 'naturalgroove/filament-image-generator-field';
    }
}
