<?php

return [
    'access_token' => env('MAPBOX_TOKEN', ''),
    'default_style' => 'mapbox://styles/mapbox/streets-v12',
    'default_zoom' => 12,
    'default_center' => [
        'lat' => 0,  // Default coordinates (equator)
        'lng' => 0,
    ],
];