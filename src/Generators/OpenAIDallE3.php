<?php

namespace NaturalGroove\Filament\ImageGeneratorField\Generators;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use NaturalGroove\Filament\ImageGeneratorField\Contracts\AIImageGenerator;
use OpenAI;

class OpenAIDallE3 implements AIImageGenerator
{
    public static string $name = 'OpenAI DALL-E 3';

    public function generate(string $prompt, int $n = 1, array $params = []): array
    {
        $client = $this->getOpenAIClient();

        $response = $client->images()->create([
            'model' => 'dall-e-3',
            'prompt' => $prompt,
            'n' => 1,
            'size' => $this->matchAspectRatio($params['aspect_ratio']),
            'response_format' => 'url',
            'quality' => $params['quality'] ?? 'standard',
            'style' => $params['style'] ?? 'natural',
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
            'aspect_ratio' => [
                '1:1' => '1:1',
                '16:9' => '16:9',
                '9:16' => '9:16',
            ],

            'quality' => [
                'standard' => 'Standard',
                'hd' => 'High definition',
            ],

            'style' => [
                'natural' => 'Natural',
                'vivid' => 'Vivid',
            ],
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
            '16:9' => '1792x1024',
            '9:16' => '1024x1792',
        ];

        return $aspectRatios[$aspectRatio] ?? '1024x1024';
    }
}
