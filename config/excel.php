 
<?php

return [

    'imports' => [
        'heading' => 'slug', // Set to 'slug' to use first row as heading
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'escape_char' => '\\',
        ],
        'xlsx' => [
            'headings' => [
                // Define default headings if needed
            ],
        ],
    ],

    'exports' => [
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'escape_char' => '\\',
        ],
        'xlsx' => [
            'headings' => [
                // Define default headings if needed
            ],
        ],
    ],

    'imports' => [
        'heading' => 'slug', // Default to using the first row as the heading row
    ],

    'exports' => [
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'escape_char' => '\\',
        ],
        'xlsx' => [
            'headings' => [
                // Define default headings if needed
            ],
        ],
    ],

    'excel' => [
        'imports' => [
            'heading' => 'slug',
            'csv' => [
                'delimiter' => ',',
                'enclosure' => '"',
                'escape_char' => '\\',
            ],
            'xlsx' => [
                'headings' => [
                    // Add default headings here if required
                ],
            ],
        ],

        'exports' => [
            'csv' => [
                'delimiter' => ',',
                'enclosure' => '"',
                'escape_char' => '\\',
            ],
            'xlsx' => [
                'headings' => [
                    // Add default headings here if required
                ],
            ],
        ],
    ],

];
