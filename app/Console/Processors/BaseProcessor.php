<?php

namespace Rowles\Console\Processors;

use Exception;
use FFMpeg\FFMpeg;
use FFMpeg\Media\Video;
use Illuminate\Console\OutputStyle;
use Log;
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

    /** @var array  */
    protected array $options;

    /** @var string  */
    public string $identifier;

    /**
     * BaseProcessor Constructor.
     *
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
     * Recursive method to transcode videos.
     *
     * Single
     * Accepts either an absolute filepath to the video, or a relative filepath which will instead be appended to env
     * VIDEO_STORAGE_SOURCE, for example, passing "/home/user/videos/video1.mp4" will trigger processing for that video,
     * whereas passing "media/video1.mp4" with VIDEO_STORAGE_SOURCE set to "/mnt/d/" will trigger processing for the
     * video "/mnt/d/media/video1.mp4"
     *
     * Bulk Mode
     * Uses env VIDEO_STORAGE_SOURCE which can be set before the command is executed. $recursiveMode will be empty
     * upon first scan, if folders are found during the first scan, this method is called again with $recursiveMode
     * populated with the folders items, this process repeats until no more sub-folders are left.
     *
     * @param mixed $name
     * @param array $recursiveData
     * @return array
     * @throws Exception
     */
    public function execute($name = null, array $recursiveData = []): array
    {
        // Disallow directory traversal
        if (substr($name, 0, 2) === '..') {
            throw new Exception('Directory traversal is not allowed.');
        }

        if (!$name && $this->options['bulk']) {
            // Scan video storage, unless we already have and we're processing files in folders recursively.
            $scan = empty($recursiveData) ? $this->getVideosFromStorage() : $recursiveData;

            if ($this->console && is_integer($scan['total'])) {
                $this->console->info($scan['total'] . ' videos to transcode');
            }

            foreach ($scan['items'] as $file) {
                if ($file['type'] === 'folder') {
                    // If we have found a folder, then repeat this process with the folder's items.
                    $this->execute($name, $file['items']);
                } else {
                    // If we have found a file, then run the transcoding task.
                    $this->ffmpegTask($file);
                }
            }
        } elseif ($name && !$this->options['bulk']) {
            $this->ffmpegTask($name);
        } else {
            Log::error(var_export(['process' => $this->identifier, 'filename' => $name, 'options' => $this->options]));
            throw new Exception('You must provide valid options, please check the logs for more information.');
        }

        if ($this->errors[$this->identifier] > 0) {
            return ['status' => 'error', 'errors' => $this->errors];
        }

        return ['status' => 'success', 'errors' => null];
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
     * @param string $path
     * @return array
     */
    public function getThumbnailsFromStorage(string $path = ""): array
    {
        return array_slice(scandir($this->thumbnailStorageSource($path)), 2);
    }

    /**
     * @param string $path
     * @param bool $isGif
     * @return string
     */
    public function thumbnailStorageSource(string $path = "", bool $isGif = false): string
    {
        if (is_file($path)) {
            return $path;
        }

        $folder = $isGif ? 'gif' : 'jpeg';
        $directory = config('storage.image.source') . '/' . $folder;

        return $directory . '/' . $path;
    }

    /**
     * @param string $path
     * @param bool $isGif
     * @return string
     */
    public function thumbnailStorageDestination(string $path, bool $isGif = false): string
    {
        if (is_file($path)) {
            return $path;
        }

        $folder = $isGif ? 'gif' : 'jpeg';
        $directory = config('storage.image.destination') . '/' . $folder;

        return $directory . '/' . $path;
    }

    /**
     * @param string $path
     * @return array
     */
    public function getPreviewsFromStorage(string $path = ""): array
    {
        return array_slice(scandir($this->previewStorageSource($path)), 2);
    }

    /**
     * @param string $path
     * @return string
     */
    public function previewStorageSource(string $path = ""): string
    {
        if (is_file($path)) {
            return $path;
        }

        return config('storage.preview.source') . '/' . $path;
    }

    /**
     * @param string $path
     * @return string
     */
    public function previewStorageDestination(string $path): string
    {
        if (is_file($path)) {
            return $path;
        }

        return config('storage.preview.destination') . '/' . $path;
    }

    /**
     * Recursively scans for videos at a location depending on whether a relative path or an absolute path is passed.
     *
     * For example, if $name = "/home/user/videos", then the recursive scan will be performed in that location, if
     * $name = "videos" and VIDEO_STORAGE_SOURCE is set to "/mnt/d/media", then the recursive scan will be performed in
     * "/mnt/d/media/videos/"
     *
     * @param string $path
     * @return array
     */
    public function getVideosFromStorage(string $path = ""): array
    {
        $videos = $this->scanRecursive($this->videoStorageSource($path));

        $videos['total'] = 0;
        $this->numberOfFiles($videos, $videos['total']);

        return $videos;
    }

    /**
     * Returns a path to the video depending on whether a relative path or an absolute path is passed.
     *
     * For example, if $name = "/home/user/videos/video.mp4", then that file will be returned, if $name = "video.mp4"
     * and VIDEO_STORAGE_SOURCE is set to "/mnt/d/media", then "/mnt/d/media/video.mp4" will be returned.
     *
     * @param string $path
     * @return string
     */
    public function videoStorageSource(string $path = ""): string
    {
        if (is_file($path)) {
            return $path;
        }

        return config('storage.video.source') . '/' . $path;
    }

    /**
     * Returns a path to the video storage destination depending on whether a relative or absolute path is passed.
     *
     * For example, if $name = "/home/user/videos/video.mp4", then that destination will be returned, if $name = "video.mp4"
     * and VIDEO_STORAGE_DESTINATION is set to "/mnt/d/transcoded", then "/mnt/d/transcoded/video.mp4" will be returned.
     *
     * @param string $path
     * @return string
     */
    public function videoStorageDestination(string $path): string
    {
        if (is_file($path)) {
            return $path;
        }

        return config('storage.video.destination') . '/' . $path;
    }

    /**
     * Map options, overriding any defaults where needed.
     *
     * @param array $options
     * @return $this
     */
    public function mapOptions(array $options) : self
    {
        foreach($options as $key => $option) {
            if (isset($this->options[$key])) {
                if (is_array($this->options[$key])) {
                    foreach($this->options[$key] as $k=>$v) {
                        if ($k === 'enable') {
                            $this->options[$key][$k] = $options[$key];
                        } else {
                            $this->options[$key][$k] = isset($options[$k]) ? $options[$k] : $v;
                        }
                    }
                } else {
                    $this->options[$key] = $option ?? $this->options[$key];
                }
            }
        }

        return $this;
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
        Metadata::whereHas('video', function ($q) use ($file) {
            return $q->where('filepath', '=', $file);
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
                        'extension' => pathinfo($f, PATHINFO_EXTENSION),
                        'size' => filesize($path . '/' . $f),
                    ];
                }
            }
        }

        return $files;
    }

    /**
     * @param $scan
     * @param $count
     */
    public function numberOfFiles($scan, &$count): void
    {
        foreach ($scan['items'] as $item) {
            if ($item['type'] === 'folder') {
                $count += $item['items']['total']['files'];
                $this->numberOfFiles($item['items'], $count);
            }
        }
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
