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
     * @param string $command
     * @return string|bool
     */
    public function getCommand(string $namespace, string $command)
    {
        if (!isset(static::$mappings[$namespace])) {
            return false;
        }

        return static::$mappings[$namespace][$command] ?? false;
    }

    /**
     * @return array
     */
    public function getAvailableCommands(): array
    {
        return Artisan::all();
    }
}
