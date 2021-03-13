<?php

namespace Rowles;

use Artisan;

class ArtisanMapper implements MapperInterface
{
    /**
     * @var array $mappings.
     */
    private static array $mappings = [
        'vid' => [
            'metadata' =>  'vid:metadata',
            'thumbnail' => 'vid:thumbnail',
            'transcode' => 'vid:transcode'
        ],
        'aws' => [
            's3' => [
                'upload' => 'aws:s3:upload'
            ]
        ]
    ];

    /**
     * @param string $command
     * @param array $options
     * @return string
     */
    public function execute(string $command, array $options = []): string
    {
        Artisan::call($command, $options);

        return Artisan::output();
    }

    /**
     * @param string $namespace
     * @param string $signature
     * @return string|bool
     */
    public function map(string $namespace, string $signature)
    {
        if (!isset(static::$mappings[$namespace])) {
            return false;
        }

        return static::$mappings[$namespace][$signature] ?? false;
    }

    /**
     * @return array
     */
    public function getAvailable(): array
    {
        return Artisan::all();
    }
}
