<?php

namespace NaturalGroove\Filament\ImageGenerator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \NaturalGroove\Filament\ImageGenerator\FilamentImageGeneratorField
 */
class FilamentImageGeneratorField extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \NaturalGroove\Filament\ImageGenerator\FilamentImageGeneratorField::class;
    }
}
