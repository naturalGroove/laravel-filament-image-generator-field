<?php

// translations for natural-groove/filament-image-generator-field

return [
    'labels' => [
        'generate-using-ai' => 'Generuj za pomocą AI',
        'edit-using-ai' => 'Edytuj za pomocą AI',
    ],

    'form' => [
        'fields' => [
            'showOptions' => 'Pokaż opcje',
            'options' => 'Opcje',

            'prompt' => 'Polecenie',
            'prompt-placeholder' => 'na przykład: `Kot siedzący na kanapie`. Staraj się być jak najbardziej opisowy.',

            'n' => 'Liczba generowanych obrazów',
            'aspect_ratio' => 'Proporcje',
            'size' => 'Rozmiar',
            'size-hint' => 'Wybierz rozmiar obrazu.',
            'style' => 'Styl',
            'style-hint' => 'Wybierz styl obrazu.',
            'quality' => 'Jakość',
            'quality-hint' => 'Wybierz jakość obrazu.',
        ],

        'errors' => [
            'no-images-generated' => 'Nie wygenerowano żadnych obrazów. Spróbuj ponownie z innym poleceniem.',
        ]
    ],

    'modals' => [
        'generate-an-image' => [
            'title' => 'Generuj obraz',
            'description' => 'Opisz obraz, który chcesz wygenerować.<br />Poczekaj kilka sekund, aż obraz będzie gotowy.',
            'generate' => 'Generuj',
            'generating' => 'Generowanie...',
            'add-generated' => 'Dodaj wygenerowany obraz',
            'cancel' => 'Anuluj',
            'select' => 'Wybierz',
            'uploading' => 'Przesyłanie...',

            'configuration-error' => 'Proszę skonfigurować klucz API w pliku konfiguracyjnym.',
        ]
    ]
];
