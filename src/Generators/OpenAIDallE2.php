<?php

namespace NaturalGroove\Filament\ImageGeneratorField\Generators;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use NaturalGroove\Filament\ImageGeneratorField\Contracts\AIImageGenerator;
use OpenAI;

class OpenAIDallE2 implements AIImageGenerator
{
    public static string $name = 'OpenAI DALL-E 2';

    public function generate(string $prompt, int $n = 1, array $params = []): array
    {
        $client = $this->getOpenAIClient();

        $response = $client->images()->create([
            'model' => 'dall-e-2',
            'prompt' => $prompt,
            'n' => $n,
            'size' => '1024x1024', // '1024x1024' is the only supported aspect ratio for DALL-E 2
            'response_format' => 'url',
        ]);

        //Log::info('OpenAI Dall-E response', $response->toArray());

        return $response->toArray()['data'];
    }

    public function fetch(string $url): string
    {
        $response = Http::get($url);

        return $response->body();
    }
    public function getName(): string
    {
        return static::$name;
    }

    public function getFileExtension(): string
    {
        return config('filament-image-generator-field.openai-dall-e.file_extension') ?? 'png';
    }

    public function getSupportedOptions(): array
    {
        return [
            'n' => [
                '1' => '1',
                '2' => '2',
                '4' => '4',
            ],

            'aspect_ratio' => [
                '1:1' => '1:1',
            ]
        ];
    }

    public function validateConfiguration(): bool
    {
        return config('filament-image-generator-field.openai-dall-e.api_key') !== null;
    }

    // ************************************************************

    protected function getOpenAIClient(): OpenAI\Client
    {
        return OpenAI::client(config('filament-image-generator-field.openai-dall-e.api_key'));
    }

    protected function matchAspectRatio(string $aspectRatio): string
    {
        $aspectRatios = [
            '1:1' => '1024x1024',
        ];

        return $aspectRatios[$aspectRatio] ?? '1024x1024';
    }
}
