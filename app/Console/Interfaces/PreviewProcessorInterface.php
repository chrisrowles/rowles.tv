<?php

namespace Rowles\Console\Interfaces;

interface PreviewProcessorInterface extends BaseProcessorInterface
{
    /**
     * @param string $name
     * @param bool $bulkMode
     * @return array
     */
    public function execute(string $name = "", bool $bulkMode = false): array;

    /**
     * @param string $name
     */
    public function ffmpegTask(string $name): void;
}
