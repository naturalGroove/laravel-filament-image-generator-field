<?php

namespace NaturalGroove\Filament\ImageGeneratorField\Forms\Components;

use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;

class ImageGenerator extends FileUpload
{
    public static bool $isComponentRegistered = false;

    protected string $view = 'filament-image-generator-field::forms.components.image-generator';

    public string | Closure | null $imageGenerator = 'openai-dall-e';

    protected function setUp(): void
    {
        parent::setUp();

        $this->image();
        $this->columnSpanFull();

        // register the modal view with component - prevent multiple registration
        if (!static::$isComponentRegistered) {
            FilamentView::registerRenderHook(
                PanelsRenderHook::CONTENT_END,
                fn (): View => view('filament-image-generator-field::modals.generate-an-image'),
            );

            static::$isComponentRegistered = true;
        }
    }

    public function getFieldWrapperView(?string $scope = null): string
    {
        if ($scope === 'generator') {
            return $this->getCustomFieldWrapperView() ??
                $this->getContainer()->getCustomFieldWrapperView() ??
                'filament-forms::field-wrapper';
        }

        return 'filament-image-generator-field::blank-field-wrapper';
    }

    public function imageGenerator(string | Closure | null $imageGenerator): static
    {
        if (is_string($imageGenerator) && !config("filament-image-generator-field.{$imageGenerator}")) {
            throw new \InvalidArgumentException("Image generator `{$imageGenerator}` is not defined in the configuration.");
        }

        $this->imageGenerator = $imageGenerator;

        return $this;
    }

    public function getImageGenerator(): ?string
    {
        return $this->evaluate($this->imageGenerator);
    }

    public function useDallE(): static
    {
        return $this->imageGenerator('openai-dall-e');
    }
}
