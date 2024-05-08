<?php

// translations for natural-groove/filament-image-generator-field

return [
    'labels' => [
        'generate-using-ai' => 'Mit AI generieren',
        'edit-using-ai' => 'Mit AI bearbeiten',
    ],

    'form' => [
        'fields' => [
            'showOptions' => 'Optionen anzeigen',
            'options' => 'Optionen',

            'prompt' => 'Aufforderung',
            'prompt-placeholder' => 'zum Beispiel: `Eine Katze sitzt auf einem Sofa`. Versuche so genau wie möglich zu sein.',

            'n' => 'Anzahl der zu generierenden Bilder',
            'aspect_ratio' => 'Seitenverhältnis',
            'size' => 'Größe',
            'size-hint' => 'Wähle die Größe des Bildes aus.',
            'style' => 'Stil',
            'style-hint' => 'Wähle den Stil des Bildes aus.',
            'quality' => 'Qualität',
            'quality-hint' => 'Wähle die Qualität des Bildes aus.',
        ],

        'errors' => [
            'no-images-generated' => 'Es wurden keine Bilder generiert. Bitte versuche es erneut mit einer anderen Aufforderung.',
        ]
    ],

    'modals' => [
        'generate-an-image' => [
            'title' => 'Bild generieren',
            'description' => 'Beschreibe das Bild, das du generieren möchtest.<br />Warte einige Sekunden, bis dein Bild fertig ist.',
            'generate' => 'Generieren',
            'generating' => 'Generiere...',
            'add-generated' => 'Generiertes Bild hinzufügen',
            'cancel' => 'Abbrechen',
            'select' => 'Auswählen',
            'uploading' => 'Hochladen...',

            'configuration-error' => 'Die Konfiguration für den ausgewählten Generator ist ungültig. Bitte überprüfen Sie die Konfiguration, wie z.B. den API-Schlüssel, und versuchen Sie es erneut.'
        ]
    ]
];
