<?php

// translations for VendorName/Skeleton
return [
    'labels' => [
        'generate-using-ai' => 'Generate using AI',
        'edit-using-ai' => 'Edit using AI',
    ],

    'fields' => [
        'prompt' => 'Prompt',
        'n' => 'Number of Images to Generate',
        'aspect_ratio' => 'Aspect Ratio',
    ],

    'modals' => [
        'generate-an-image' => [
            'title' => 'Generate an Image',
            'description' => 'Please provide a prompt to generate an image. You can also specify the number of images to generate. Wait for a few seconds for the image to be generated.',
            'generate' => 'Generate',
            'add-generated' => 'Add generated image',
            'cancel' => 'Cancel',
            'downloading' => 'Downloading...',

            'configuration-error' => 'Please configure the OpenAI API key in the configuration file.',
        ]
    ]
];
