<?php

// translations for natural-groove/filament-image-generator-field

return [
    'labels' => [
        'generate-using-ai' => 'Generate using AI',
        'edit-using-ai' => 'Edit using AI',
    ],

    'form' => [
        'fields' => [
            'showOptions' => 'Show Options',
            'options' => 'Options',

            'prompt' => 'Prompt',
            'prompt-placeholder' => 'for example: `A cat sitting on a couch`. Try to be as descriptive as possible.',

            'n' => 'Number of Images to Generate',
            'aspect_ratio' => 'Aspect ratio',
            'size' => 'Size',
            'size-hint' => 'Select the size of the image.',
            'style' => 'Style',
            'style-hint' => 'Select the style of the image.',
            'quality' => 'Quality',
            'quality-hint' => 'Select the quality of the image.',
        ],

        'errors' => [
            'no-images-generated' => 'No images were generated. Please try again with a different prompt.',
        ]
    ],

    'modals' => [
        'generate-an-image' => [
            'title' => 'Generate an Image',
            'description' => 'Describe the image You want to generate.<br />Wait for a few seconds until your image is ready.',
            'generate' => 'Generate',
            'generating' => 'Generating...',
            'add-generated' => 'Add generated image',
            'cancel' => 'Cancel',
            'select' => 'Select',
            'uploading' => 'Uploading...',

            'configuration-error' => 'Please configure the API key in the configuration file.',
        ]
    ]
];
