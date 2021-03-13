<?php

namespace Rowles;

use Artisan;

class ArtisanMapper
{
    /**
     * @var array $mappings.
     */
    private static array $mappings = [
        'vid' => [
            'metadata' => 'vid:metadata',
            'thumbnail' => 'vid:thumbnail',
            'preview' => 'vid:previews',
            'transcode' => 'vid:transcode',
        ],
        'aws' => [
            's3' => [
                'upload' => 'aws:s3:upload'
            ]
        ]
    ];

    /**
     * Execute command.
     *
     * @param string $command
     * @return string
     */
    public function execute(string $command): string
    {
        Artisan::call($command);

        return Artisan::output();
    }

    /**
     * Get command.
     *
     * @param string $namespace
     * @param string $command
     * @return mixed
     */
    public function getCommand(string $namespace, string $command)
    {
        return static::$mappings[$namespace][$command] ?? false;
    }
}
