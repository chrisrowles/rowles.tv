<?php

namespace Rowles\Console\Interfaces;

interface ThumbnailProcessorInterface extends BaseProcessorInterface
{
    /**
     * @param string $name
     * @param bool $isGif
     * @param bool $bulkMode
     * @return array
     */
    public function execute(string $name = "", bool $isGif = false, bool $bulkMode = false): array;

    /**
     * @param string $name
     * @param bool $isGif
     * @return mixed
     */
    public function ffmpegTask(string $name, bool $isGif);
}
