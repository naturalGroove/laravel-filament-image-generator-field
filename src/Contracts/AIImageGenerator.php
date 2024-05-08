<?php

namespace NaturalGroove\Filament\ImageGeneratorField\Contracts;

/**
 * @internal
 */
interface AIImageGenerator
{
    /**
     * Generate an image.
     * @param string $prompt
     * @param int $n
     * @param array $params
     * @return array
     */
    public function generate(string $prompt, int $n = 1, array $params = []): array;

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

    /**
     * Get the supported options for the generator.
     * @return array
     */
    public function getSupportedOptions(): array;

    /**
     * Verify the configuration of the generator.
     * @return bool
     */
    public function validateConfiguration(): bool;
}
