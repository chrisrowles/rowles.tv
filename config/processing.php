<?php

return [
    'binaries' => [
        'ffprobe' => env('FFPROBE_BINARY', '/usr/bin/ffprobe'),
        'ffmpeg' => env('FFMPEG_BINARY', '/usr/bin/ffmpeg'),
    ],
    'threads' => env('FFMPEG_THREADS', 12),
    'timeout' => env('FFMPEG_TIMEOUT', 300)
];
