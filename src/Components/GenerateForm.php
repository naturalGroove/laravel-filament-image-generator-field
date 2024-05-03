<?php

namespace NaturalGroove\Filament\ImageGeneratorField\Components;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use NaturalGroove\Filament\ImageGeneratorField\Services\DownloadImageFromUrl;
use Illuminate\Support\Str;
use NaturalGroove\Filament\ImageGeneratorField\Contracts\ImageGenerator;

class GenerateForm extends Component implements HasForms
{
    use InteractsWithForms;

    public bool $isConfigurationOk = false;

    public string|array|null $url = null;

    protected ImageGenerator $generator;

    // properties for form fields
    public ?string $prompt = null;
    public int $n = 1;
    public string $aspect_ratio = '1:1';
    public string $quality = 'standard';

    public function mount(): void
    {
        $this->getForm('promptForm')?->fill();

        // check if the OpenAI API key is set
        if (config('filament-image-generator-field.openai-dall-e.api_key')) {
            $this->isConfigurationOk = true;
        }
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
                        ->label('Prompt')
                        ->columnSpan(2)
                        ->placeholder('Describe the image you want to generate. For example: `A cat sitting on a couch`. Try to be as descriptive as possible.')
                        ->rows(4)
                        ->rules(['string', 'min:10'])
                        ->default($this->prompt)
                        ->required(),
                    // Select::make('n')
                    //     ->label('Number of Images to Generate')
                    //     ->options([
                    //         1 => '1',
                    //         2 => '2',
                    //     ])
                    //     ->rules(['integer'])
                    //     ->default(1),
                    Select::make('aspect_ratio')
                        ->label('Aspect Ratio')
                        ->hint('Select an aspect ratio.')
                        ->options([
                            '1:1' => '1:1',
                            '16:9' => '16:9',
                            '9:16' => '9:16',
                        ])
                        ->default('1:1')
                        ->rules(['string'])
                        ->required(),
                    Select::make('quality')
                        ->label('Quality')
                        ->hint('Select the quality of the image.')
                        ->options([
                            'standard' => 'standard',
                            'hd' => 'HD',
                        ])
                        ->default('standard')
                        ->rules(['string'])
                        ->required(),
                ])
        ];
    }

    public function generateImage(string $generator): void
    {
        $this->validate();

        $this->verifyGenerator($generator);

        try {
            $this->generator = $this->getGeneratorObject($generator);

            $this->url = $this->generator->generate($this->prompt ?? '', $this->n, [
                'aspect_ratio' => $this->aspect_ratio,
                'quality' => $this->quality
            ]);
        } catch (\Exception $e) {
            $this->addError('prompt', $e->getMessage());
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

    protected function getGeneratorObject(string $generator): ImageGenerator
    {
        // @phpstan-ignore-next-line
        return new (config("filament-image-generator-field.generators.{$generator}"))();
    }
}
