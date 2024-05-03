<?php

namespace NaturalGroove\Filament\ImageGeneratorField\Generators;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use NaturalGroove\Filament\ImageGeneratorField\Contracts\ImageGenerator;
use OpenAI;

class OpenAIDallE implements ImageGenerator
{
    public function generate(string $prompt, int $n = 1, array $params = []): string | array
    {
        $client = $this->getOpenAIClient();

        $response = $client->images()->create([
            'model' => 'dall-e-3',
            'prompt' => $prompt,
            'n' => $n,
            'size' => $this->matchAspectRatio($params['aspect_ratio']),
            'response_format' => 'url',
        ]);

        Log::info('OpenAI Dall-E response', $response->toArray());

        if ($n === 1) {
            return $response->data[0]->url;
        }

        return $response->toArray();
    }

    public function fetch(string $url): string
    {
        $response = Http::get($url);

        return $response->body();
    }

    public function getFileExtension(): string
    {
        return config('filament-image-generator-field.openai-dall-e.file_extension') ?? 'png';
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
