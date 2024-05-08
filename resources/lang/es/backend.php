<?php

// translations for natural-groove/filament-image-generator-field

return [
    'labels' => [
        'generate-using-ai' => 'Generar usando IA',
        'edit-using-ai' => 'Editar usando IA',
    ],

    'form' => [
        'fields' => [
            'showOptions' => 'Mostrar opciones',
            'options' => 'Opciones',

            'prompt' => 'Indicación',
            'prompt-placeholder' => 'por ejemplo: `Un gato sentado en un sofá`. Intenta ser lo más descriptivo posible.',

            'n' => 'Número de imágenes a generar',
            'aspect_ratio' => 'Relación de aspecto',
            'size' => 'Tamaño',
            'size-hint' => 'Selecciona el tamaño de la imagen.',
            'style' => 'Estilo',
            'style-hint' => 'Selecciona el estilo de la imagen.',
            'quality' => 'Calidad',
            'quality-hint' => 'Selecciona la calidad de la imagen.',
        ],

        'errors' => [
            'no-images-generated' => 'No se generaron imágenes. Por favor, intenta de nuevo con una indicación diferente.',
        ]
    ],

    'modals' => [
        'generate-an-image' => [
            'title' => 'Generar una imagen',
            'description' => 'Describe la imagen que quieres generar.<br />Espera unos segundos hasta que tu imagen esté lista.',
            'generate' => 'Generar',
            'generating' => 'Generando...',
            'add-generated' => 'Agregar imagen generada',
            'cancel' => 'Cancelar',
            'select' => 'Seleccionar',
            'uploading' => 'Subiendo...',

            'configuration-error' => 'Por favor, configura la clave API en el archivo de configuración.',
        ]
    ]
];
