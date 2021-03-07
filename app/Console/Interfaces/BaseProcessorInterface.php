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
     * @param string $path
     * @return array
     */
    public function getThumbnailsFromStorage(string $path = ""): array;

    /**
     * @param string $path
     * @param bool $isGif
     * @return string
     */
    public function thumbnailStorageSource(string $path = "", bool $isGif = false): string;

    /**
     * @param string $path
     * @param bool $isGif
     * @return string
     */
    public function thumbnailStorageDestination(string $path, bool $isGif = false): string;

    /**
     * @param string $path
     * @return array
     */
    public function getPreviewsFromStorage(string $path = ""): array;

    /**
     * @param string $path
     * @return string
     */
    public function previewStorageSource(string $path = ""): string;

    /**
     * @param string $path
     * @return string
     */
    public function previewStorageDestination(string $path): string;

    /**
     * @param string $path
     * @return array
     */
    public function getVideosFromStorage(string $path = ""): array;

    /**
     * @param string $path
     * @return string
     */
    public function videoStorageSource(string $path = ""): string;

    /**
     * @param string $path
     * @return string
     */
    public function videoStorageDestination(string $path): string;

    /**
     * @param array $options
     * @return $this
     */
    public function mapOptions(array $options): self;

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
     * @param mixed $path
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
