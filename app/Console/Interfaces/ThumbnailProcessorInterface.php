<?php

namespace Rowles\Console\Interfaces;

use Exception;

interface ThumbnailProcessorInterface extends BaseProcessorInterface
{
    /**
     * @param mixed $name
     * @param array $recursiveData
     * @return array
     * @throws Exception
     */
    public function execute($name = null, array $recursiveData = []): array;

    /**
     * @param mixed $item
     * @return void
     */
    public function ffmpegTask($item): void;
}
