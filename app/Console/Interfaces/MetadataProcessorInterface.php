<?php

namespace Rowles\Console\Interfaces;

use Rowles\Models\Video;
use FFMpeg\Exception\InvalidArgumentException;

interface MetadataProcessorInterface extends BaseProcessorInterface
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

    /**
     * @param Video $video
     * @return int
     */
    public function getFileSize(Video $video): int;

    /**
     * @param Video $video
     * @return string
     * @throws InvalidArgumentException
     */
    public function getFormat(Video $video): string;

    /**
     * @param Video $video
     * @return string
     * @throws InvalidArgumentException
     */
    public function getCodec(Video $video): string;

    /**
     * @param Video $video
     * @return int
     * @throws InvalidArgumentException
     */
    public function getBitrate(Video $video): int;

    /**
     * @param Video $video
     * @return float
     * @throws InvalidArgumentException
     */
    public function getDuration(Video $video): float;
}
