<?php

namespace Rowles\Console\Interfaces;

interface PreviewProcessorInterface extends BaseProcessorInterface
{
    /**
     * @param string $name
     * @param bool $bulkMode
     * @param array $recursiveMode
     * @return array
     */
    public function execute(string $name = "", bool $bulkMode = false, array $recursiveMode = []): array;

    /**
     * @param array|string $item
     */
    public function ffmpegTask($item): void;
}
