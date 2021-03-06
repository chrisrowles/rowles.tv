<?php

namespace Rowles\Console\Interfaces;

interface ThumbnailProcessorInterface extends BaseProcessorInterface
{
    /**
     * @param string $name
     * @param bool $isGif
     * @param bool $bulkMode
     * @param array $recursiveMode
     * @return array
     */
    public function execute(string $name = "", bool $isGif = false, bool $bulkMode = false, array $recursiveMode = []): array;

    /**
     * @param array|string $item
     * @param bool $isGif
     * @return mixed
     */
    public function ffmpegTask($item, bool $isGif);
}
