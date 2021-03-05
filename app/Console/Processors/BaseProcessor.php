<?php

namespace Rowles\Console\Processors;

use FFMpeg\FFMpeg;
use FFMpeg\Media\Video;
use Illuminate\Console\OutputStyle;
use Rowles\Console\OutputFormatter;
use FFMpeg\Exception\InvalidArgumentException;
use Rowles\Console\Interfaces\BaseProcessorInterface;
use FFMpeg\Format\Video\{DefaultVideo, X264, WMV, WebM};
use Rowles\Models\Metadata;

class BaseProcessor implements BaseProcessorInterface
{
    /** @var FFMpeg $ffmpeg */
    protected FFMpeg $ffmpeg;

    /** @var int $start */
    protected int $start = 10;

    /** @var int $seconds */
    protected int $seconds = 10;

    /** @var mixed $console */
    protected $console = false;

    /**
     * @param mixed $console
     */
    public function __construct($console = false)
    {
        $this->ffmpeg = FFMpeg::create([
            'ffprobe.binaries' => config('processing.binaries.ffprobe'),
            'ffmpeg.binaries' => config('processing.binaries.ffmpeg'),
            'ffmpeg.threads' => config('processing.threads'),
            'timeout' => config('processing.timeout'),
        ]);

        if ($console) {
            $this->console = new OutputFormatter($console);
        }
    }

    /**
     * @param OutputStyle $console
     * @return $this
     */
    public function setConsole(OutputStyle $console): self
    {
        $this->console = new OutputFormatter($console);
        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setStart(int $value): self
    {
        $this->start = $value;
        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setSeconds(int $value): self
    {
        $this->seconds = $value;
        return $this;
    }

    /**
     * @param string $name
     * @return Video
     * @throws InvalidArgumentException
     */
    public function openVideo(string $name): Video
    {
        return $this->ffmpeg->open($name);
    }

    /**
     * @param string $name
     * @return array
     */
    public function getThumbnailsFromStorage(string $name = ""): array
    {
        return array_slice(scandir($this->thumbnailStorageSource($name)), 2);
    }

    /**
     * @param string $name
     * @param bool $isGif
     * @return string
     */
    public function thumbnailStorageSource(string $name = "", bool $isGif = false): string
    {
        if (is_file($name)) {
            return $name;
        }

        $folder = $isGif ? 'gif' : 'jpeg';
        $directory = config('storage.image.source') . '/' . $folder;

        return $directory . '/' . $name;
    }

    /**
     * @param string $name
     * @param bool $isGif
     * @return string
     */
    public function thumbnailStorageDestination(string $name, bool $isGif = false): string
    {
        if (is_file($name)) {
            return $name;
        }

        $folder = $isGif ? 'gif' : 'jpeg';
        $directory = config('storage.image.destination') . '/' . $folder;

        return $directory . '/' . $name;
    }

    /**
     * @param string $name
     * @return array
     */
    public function getPreviewsFromStorage(string $name = ""): array
    {
        return array_slice(scandir($this->previewStorageSource($name)), 2);
    }

    /**
     * @param string $name
     * @return string
     */
    public function previewStorageSource(string $name = ""): string
    {
        if (is_file($name)) {
            return $name;
        }

        return config('storage.preview.source') . '/' . $name;
    }

    /**
     * @param string $name
     * @return string
     */
    public function previewStorageDestination(string $name): string
    {
        if (is_file($name)) {
            return $name;
        }

        return config('storage.preview.destination') . '/' . $name;
    }

    /**
     * @param string $name
     * @return array
     */
    public function getVideosFromStorage(string $name = ""): array
    {
        return $this->scanRecursive($this->videoStorageSource($name));
    }

    /**
     * @param string $name
     * @return string
     */
    public function videoStorageSource(string $name = ""): string
    {
        if (is_file($name)) {
            return $name;
        }

        return config('storage.video.source') . '/' . $name;
    }

    /**
     * @param string $name
     * @return string
     */
    public function videoStorageDestination(string $name): string
    {
        if (is_file($name)) {
            return $name;
        }

        return config('storage.video.destination') . '/' . $name;
    }

    /**
     * @param string $ext
     * @return WebM|WMV|X264
     */
    public function getNewFormat($ext = null): DefaultVideo
    {
        switch ($ext) {
            case 'wmv':
                $format = new WMV('wmav2', 'wmv2');
                break;
            case 'webm':
                $format = new WebM('libvorbis', 'libvpx');
                break;
            default:
                $format = new X264('aac', 'libx264');
                break;
        }

        return $format;
    }

    /**
     * @param string $file
     * @param array $attributes
     */
    public function updateMetadataAttribute(string $file, array $attributes): void
    {
        Metadata::whereHas('video', function($q) use ($file) {
            return $q->where('filepath', '=', $this->videoStorageSource($file));
        })->update($attributes);
    }

    /**
     * @param $path
     * @return array
     */
    public function scanRecursive($path): array
    {
        $files = [
            'items' => [],
            'total' => [
                'files' => 0,
                'folders' => 0
            ]
        ];

        if (file_exists($path)) {

            foreach (scandir($path) as $f) {
                $path = substr($path, -1) === '/' ? substr($path, 0, -1) : $path;

                if (!$f || $f[0] == '.') {
                    continue;
                }

                if (is_dir($path . '/' . $f)) {
                    ++$files['total']['folders'];
                    $files['items'][] = [
                        'name' => $f,
                        'type' => 'folder',
                        'path' => $path . '/' . $f,
                        'items' => $this->scanRecursive($path . '/' . $f)
                    ];
                } else {
                    ++$files['total']['files'];
                    $files['items'][] = [
                        'name' => $f,
                        'type' => "file",
                        'path' => $path . '/' . $f,
                        'size' => filesize($path . '/' . $f)
                    ];
                }
            }
        }

        return $files;
    }

    /**
     * @param string $file
     * @return bool
     */
    public function extAllowed(string $file): bool
    {
        $info = pathinfo($file);
        if (!isset($info['extension'])) {
            return false;
        }

        $allowed = [
            'webm',
            'mkv',
            'ogg',
            'ogv',
            'gif',
            'gifv',
            'avi',
            'mov',
            'wmv',
            'mp4'
        ];

        if (!in_array($info['extension'], $allowed)) {
            return false;
        }

        return true;
    }
}
