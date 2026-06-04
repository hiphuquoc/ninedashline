<?php

return [
    'fonts' => [
        'sans' => 'Lexend',
        'serif_title' => 'Playfair Display',
        'serif_text' => 'Cormorant Garamond',
        'display' => 'Barlow Condensed',
        'google_fonts_url' => 'https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700;900&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Lexend:wght@200;300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,500&display=swap',
    ],
    'images_base' => env('LANDING_IMAGES_BASE', rtrim(env('APP_URL', 'https://ninedashline.dev'), '/') . ''),
    'img_placeholder' => 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
    'ambient_audio' => '/storage/sounds/hello-viet-nam.mp3',
];
