<?php

return [
    // Define the generators that can be used
    // The key is the name of the generator and the value is the class that implements the generator
    'generators' => [
        'openai-dall-e' => \NaturalGroove\Filament\ImageGeneratorField\Generators\OpenAIDallE::class,
    ],

    // Define the configuration for each generator
    // The key is the name of the generator and the value is an array of configuration options
    // The configuration options are specific to each generator
    // For example, the OpenAI DALL-E generator requires an API key
    'openai-dall-e' => [
        'api_key' => env('OPEN_AI_DALL_E_API_KEY', null),
        'output-format' => 'png',
    ]
];
