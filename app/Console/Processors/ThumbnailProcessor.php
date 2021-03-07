<?php

namespace Rowles\Console\Processors;

use Log;
use Exception;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\Dimension;
use Rowles\Console\Interfaces\ThumbnailProcessorInterface;

class ThumbnailProcessor extends BaseProcessor implements ThumbnailProcessorInterface
{
    /** @var array */
    protected array $errors = ['thumbnails' => 0];

    /** @var array  */
    protected array $options;

    /**
     * TranscodeProcessor constructor.
     *
     * @param bool $console
     */
    public function __construct($console = false)
    {
        $this->options = [
            'bulk' => false,
            'gif' => [
                'enable' => false,
                'from' => 40,
                'duration' => 5
            ]
        ];

        parent::__construct($console);
    }

    /**
     * Recursive method to generate thumbnails.
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
        if (substr($name, 0, 2) === '..') return ['status' => 'error', 'errors' => ['transcode' => 1]];

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
            Log::error(var_export(['process' => 'transcode', 'filename' => $name, 'options' => $this->options]));
            throw new Exception('You must provide valid options, please check the logs for more information.');
        }

        if ($this->errors['thumbnails'] > 0) {
            return ['status' => 'error', 'errors' => $this->errors];
        }

        return ['status' => 'success', 'errors' => null];
    }

    /**
     * FFMPEG processing task to transcode videos.
     *
     * @param mixed $item
     * @return void
     */
    public function ffmpegTask($item) : void
    {
        if (is_array($item) && isset($item['path'])) {
            // If we are bulk processing, then pass the bulk processing format
            $video = $item['path'];
        } else {
            // Otherwise just fetch the single file
            $video = $this->videoStorageSource($item);
        }

        try {
            $media = $this->openVideo($video);
            $filename = is_array($item) ? $item['name'] : pathinfo($item)['basename'];

            if ($this->console) {
                $this->console->info('generating thumbnail for ' . $video);
            }

            if ($this->options['gif']['enable']) {
                $filename .= '.gif';
                $storageDestination = $this->thumbnailStorageDestination($filename, true);
                $media->gif(
                    TimeCode::fromSeconds($this->options['gif']['from']),
                    new Dimension($this->options['resize']['width'], $this->options['resize']['height']),
                    $this->options['gif']['duration']
                )->save($storageDestination);
            } else {
                $filename .= '.jpg';
                $storageDestination = $this->thumbnailStorageDestination($filename);
                $media->frame(TimeCode::fromSeconds($this->options['gif']['from']))->save($storageDestination);
            }

            if ($this->console) {
                $this->console->success('[' . $filename . '] thumbnail created');
            }
        } catch (Exception $e) {
            if ($this->console) {
                $this->console->error("[" . $video . "] " . $e->getMessage() . "\n\n");
            }

            ++$this->errors['thumbnails'];
        }
    }
}
