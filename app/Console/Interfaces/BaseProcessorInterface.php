<?php

namespace Rowles\Console\Interfaces;

use FFMpeg\Media\Video;
use FFMpeg\Format\Video\WMV;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Format\Video\X264;
use Illuminate\Console\OutputStyle;
use FFMpeg\Format\Video\DefaultVideo;
use FFMpeg\Exception\InvalidArgumentException;

interface BaseProcessorInterface
{
    /**
     * @param OutputStyle $console
     * @return $this
     */
    public function setConsole(OutputStyle $console): self;

    /**
     * @param int $value
     * @return $this
     */
    public function setStart(int $value): self;

    /**
     * @param int $value
     * @return $this
     */
    public function setSeconds(int $value): self;

    /**
     * @param string $name
     * @return Video
     * @throws InvalidArgumentException
     */
    public function openVideo(string $name): Video;

    /**
     * @param string $name
     * @return array
     */
    public function getThumbnailsFromStorage(string $name = ""): array;

    /**
     * @param string $name
     * @param bool $isGif
     * @return string
     */
    public function thumbnailStorageSource(string $name = "", bool $isGif = false): string;

    /**
     * @param string $name
     * @param bool $isGif
     * @return string
     */
    public function thumbnailStorageDestination(string $name, bool $isGif = false): string;

    /**
     * @param string $name
     * @return array
     */
    public function getPreviewsFromStorage(string $name = ""): array;

    /**
     * @param string $name
     * @return string
     */
    public function previewStorageSource(string $name = ""): string;

    /**
     * @param string $name
     * @return string
     */
    public function previewStorageDestination(string $name): string;

    /**
     * @param string $name
     * @return array
     */
    public function getVideosFromStorage(string $name = ""): array;

    /**
     * @param string $name
     * @return string
     */
    public function videoStorageSource(string $name = ""): string;

    /**
     * @param string $name
     * @return string
     */
    public function videoStorageDestination(string $name): string;

    /**
     * @param string $ext
     * @return WebM|WMV|X264
     */
    public function getNewFormat($ext = null): DefaultVideo;

    /**
     * @param string $file
     * @param array $attributes
     */
    public function updateMetadataAttribute(string $file, array $attributes): void;

    /**
     * @param $path
     * @return array
     */
    public function scanRecursive($path): array;

    /**
     * @param $scan
     * @param $count
     */
    public function numberOfFiles($scan, &$count): void;

    /**
     * @param string $file
     * @return bool
     */
    public function extAllowed(string $file): bool;
}
