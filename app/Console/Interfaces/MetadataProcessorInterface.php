<?php

namespace Rowles\Console\Interfaces;

use FFMpeg\Exception\InvalidArgumentException;

interface MetadataProcessorInterface extends BaseProcessorInterface
{
    /**
     * @param string $name
     * @param bool $bulkMode
     * @param bool|array $recursiveMode
     * @return array
     */
    public function execute(string $name = "", bool $bulkMode = false, array $recursiveMode = []): array;

    /**
     * @param mixed $item
     */
    public function ffmpegTask(string $item): void;

    /**
     * @param string $path
     * @return int
     */
    public function getFileSize(string $path): int;

    /**
     * @param string $path
     * @return string
     * @throws InvalidArgumentException
     */
    public function getFormat(string $path): string;

    /**
     * @param string $path
     * @return string
     * @throws InvalidArgumentException
     */
    public function getCodec(string $path): string;

    /**
     * @param string $path
     * @return int
     * @throws InvalidArgumentException
     */
    public function getBitrate(string $path): int;

    /**
     * @param string $path
     * @return float
     * @throws InvalidArgumentException
     */
    public function getDuration(string $path): float;
}
