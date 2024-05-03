<?php

namespace NaturalGroove\Filament\ImageGeneratorField\Contracts;

/**
 * @internal
 */
interface ImageGenerator
{
    /**
     * Generate an image.
     * @param string $prompt
     * @param int $n
     * @param array $params
     * @return string|array
     */
    public function generate(string $prompt, int $n = 1, array $params = []): string | array;

    /**
     * Fetch an image from an URL.
     * @param string $url
     * @return string local image path
     */
    public function fetch(string $url): string;

    /**
     * Get the file extension of the generated image.
     * @return string
     */
    public function getFileExtension(): string;
}
