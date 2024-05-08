<?php

namespace NaturalGroove\Filament\ImageGeneratorField\Components;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use NaturalGroove\Filament\ImageGeneratorField\Services\DownloadImageFromUrl;
use Illuminate\Support\Str;
use NaturalGroove\Filament\ImageGeneratorField\Contracts\AIImageGenerator;

class GenerateForm extends Component implements HasForms
{
    use InteractsWithForms;

    public bool $isConfigurationOk = false;
    public bool $showOptions = false;

    public array $generatedImages = [];
    public ?string $url = null;

    public ?string $generatorName = null;
    protected ?AIImageGenerator $generator = null;

    // properties for form fields
    public ?string $prompt = null;

    public int $n = 1;
    public ?string $aspect_ratio = null;
    public ?string $size = null;
    public ?string $quality = null;
    public ?string $style = null;

    public function mount(): void
    {
        // check if the OpenAI API key is set
        if (config('filament-image-generator-field.openai-dall-e.api_key')) {
            $this->isConfigurationOk = true;
        }

        $this->updateImageGenerator(config('filament-image-generator-field.default-generator'));
    }

    public function render(): View
    {
        return view('filament-image-generator-field::livewire.generate-form');
    }

    protected function getForms(): array
    {
        return [
            'promptForm' => $this->makeForm()
                ->columns(2)
                ->schema([
                    Textarea::make('prompt')
                        ->translateLabel()
                        ->label('filament-image-generator-field::backend.form.fields.prompt')
                        ->columnSpan(2)
                        ->placeholder(__('filament-image-generator-field::backend.form.fields.prompt-placeholder'))
                        ->rows(5)
                        ->rules(['string', 'min:10'])
                        ->default($this->prompt)
                        ->required(),
                    Checkbox::make('showOptions')
                        ->translateLabel()
                        ->label('filament-image-generator-field::backend.form.fields.showOptions')
                        ->default(false)
                        ->live(),
                    Fieldset::make(__('filament-image-generator-field::backend.form.fields.options'))
                        ->visible(fn (Get $get): bool => $get('showOptions'))
                        ->schema([
                            Select::make('n')
                                ->translateLabel()
                                ->label('filament-image-generator-field::backend.form.fields.n')
                                ->options(fn () => $this->getGeneratorObject($this->generatorName)->getSupportedOptions()['n'] ?? [1 => 1])
                                ->visible(fn (): bool => isset($this->getGeneratorObject($this->generatorName)->getSupportedOptions()['n']))
                                ->rules(['integer'])
                                ->default(1),
                            Select::make('aspect_ratio')
                                ->translateLabel()
                                ->label('filament-image-generator-field::backend.form.fields.aspect_ratio')
                                ->options(fn () => $this->getGeneratorObject($this->generatorName)->getSupportedOptions()['aspect_ratio'] ?? [])
                                ->visible(fn (): bool => isset($this->getGeneratorObject($this->generatorName)->getSupportedOptions()['aspect_ratio']))
                                ->rules(['string'])
                                ->required(),
                            Select::make('size')
                                ->translateLabel()
                                ->label('filament-image-generator-field::backend.form.fields.size')
                                ->hint(__('filament-image-generator-field::backend.form.fields.size-hint'))
                                ->options(fn () => $this->getGeneratorObject($this->generatorName)->getSupportedOptions()['size'] ?? [])
                                ->visible(fn (): bool => isset($this->getGeneratorObject($this->generatorName)->getSupportedOptions()['size']))
                                ->rules(['string'])
                                ->required(),
                            Select::make('style')
                                ->translateLabel()
                                ->label('filament-image-generator-field::backend.form.fields.style')
                                ->hint(__('filament-image-generator-field::backend.form.fields.style-hint'))
                                ->options(fn () => $this->getGeneratorObject($this->generatorName)->getSupportedOptions()['style'] ?? [])
                                ->visible(fn (): bool => isset($this->getGeneratorObject($this->generatorName)->getSupportedOptions()['style']))
                                ->rules(['string'])
                                ->required(),
                            Select::make('quality')
                                ->translateLabel()
                                ->label('filament-image-generator-field::backend.form.fields.quality')
                                ->hint(__('filament-image-generator-field::backend.form.fields.quality-hint'))
                                ->options(fn () => $this->getGeneratorObject($this->generatorName)->getSupportedOptions()['quality'] ?? [])
                                ->visible(fn (): bool => isset($this->getGeneratorObject($this->generatorName)->getSupportedOptions()['quality']))
                                ->rules(['string'])
                                ->required(),
                        ])
                        ->columns(1)
                ])
        ];
    }

    public function generateImage(string $generator): void
    {
        // empty the generated images array
        $this->generatedImages = [];
        $this->url = null;

        $this->validate();

        $this->verifyGenerator($generator);

        try {
            $this->generator = $this->getGeneratorObject($generator);

            $this->generatedImages = $this->generator->generate($this->prompt ?? '', $this->n, [
                'aspect_ratio' => $this->aspect_ratio ?? null,
                'size' => $this->size ?? null,
                'quality' => $this->quality ?? null,
                'style' => $this->style ?? null,
            ]);

            $this->processGeneratedImages();
        } catch (\Exception $e) {
            $this->addError('prompt', $e->getMessage());
        }
    }

    public function selectImage(int $index): void
    {
        $this->url = $this->generatedImages[$index]['url'];
    }

    #[On('update-image-generator')]
    public function updateImageGenerator(string $generator): void
    {
        if ($this->generatorName !== $generator) {
            $this->verifyGenerator($generator);

            $this->generatorName = $generator;
            $this->generator = $this->getGeneratorObject($this->generatorName);

            // set the default values for the form fields
            $defaultFields = [];

            // set the default values for the form fields
            foreach ($this->generator->getSupportedOptions() as $key => $options) {
                $this->{$key} = array_key_first($options);
                $defaultFields[$key] = $this->{$key};
            }

            $this->showOptions = false;

            $this->getForm('promptForm')?->fill(
                array_merge(
                    $defaultFields,
                    [
                        'n' => $this->n
                    ]
                )
            );
        }
    }

    #[On('add-selected')]
    public function addSelected(array $image, string $statePath, string $disk, string $generator): void
    {
        $this->generator = $this->getGeneratorObject($generator);

        $localFileName = (new DownloadImageFromUrl())->saveToDisk($this->generator->fetch($image['url']), $disk, $this->generator->getFileExtension());

        $this->dispatch('generated-image-uploaded', uuid: Str::uuid()->toString(), localFileName: $localFileName, statePath: $statePath);
    }

    protected function verifyGenerator(string $generator): void
    {
        if (config("filament-image-generator-field.generators.{$generator}") === null) {
            throw new \InvalidArgumentException("Image generator `{$generator}` is not defined in the configuration.");
        }
    }

    protected function getGeneratorObject(?string $generator): AIImageGenerator
    {
        if ($this->generator === null) {
            // @phpstan-ignore-next-line
            $this->generator =  new (config("filament-image-generator-field.generators.{$generator}"))();
        }

        // @phpstan-ignore-next-line
        return $this->generator;
    }

    protected function processGeneratedImages(): void
    {
        if (count($this->generatedImages) === 0) {
            $this->addError('prompt', __('filament-image-generator-field::backend.form.errors.no-images-generated'));
        }

        if (count($this->generatedImages) === 1) {
            $this->url = $this->generatedImages[0]['url'];
        }

        if (count($this->generatedImages) > 1) {
            // TODO: Implement multiple images - if needed
        }
    }
}
