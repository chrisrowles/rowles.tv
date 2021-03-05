<?php

return [
    'image' => [
        'source' => env('IMAGE_STORAGE_SOURCE'),
        'destination' => env('IMAGE_STORAGE_DESTINATION')
    ],
    'preview' => [
        'source' => env('PREVIEW_STORAGE_SOURCE'),
        'destination' => env('PREVIEW_STORAGE_DESTINATION')
    ],
    'video' => [
        'source' => env('VIDEO_STORAGE_SOURCE'),
        'destination' => env('VIDEO_STORAGE_DESTINATION')
    ]
];
