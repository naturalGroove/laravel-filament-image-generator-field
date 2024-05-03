<?php

// config for VendorName/Skeleton
return [
    'generators' => [
        'openai-dall-e' => \NaturalGroove\Filament\ImageGeneratorField\Generators\OpenAIDallE::class,
    ],

    'openai-dall-e' => [
        'api_key' => env('OPEN_AI_DALL_E_API_KEY', null),
        'output-format' => 'png',
    ]
];
