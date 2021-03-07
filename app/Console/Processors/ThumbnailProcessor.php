<?php

namespace Rowles\Console\Processors;

use Exception;
use FFMpeg\Coordinate\{Dimension, TimeCode};
use Rowles\Console\Interfaces\ThumbnailProcessorInterface;

class ThumbnailProcessor extends BaseProcessor implements ThumbnailProcessorInterface
{
    /** @var array $errors */
    protected array $errors = ['thumbnails' => 0];

    /**
     * ThumbnailProcessor constructor.
     *
     * @param bool $console
     */
    public function __construct($console = false)
    {
        parent::__construct($console);
    }

    /**
     * Recursive method to generate video thumbnails.
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
     * @param string $name
     * @param bool $isGif
     * @param bool $bulkMode
     * @param array $recursiveMode
     * @return array
     */
    public function execute(string $name = "", bool $isGif = false, bool $bulkMode = false, array $recursiveMode = []): array
    {
        if (substr($name, "1") === '..') return [];

        if ($bulkMode) {
            $scan = empty($recursiveMode) ? $this->getVideosFromStorage() : $recursiveMode;

            if ($this->console && is_integer($scan['total'])) {
                $this->console->info($scan['total'] . ' thumbnails to generate');
            }

            foreach ($scan['items'] as $file) {
                if ($file['type'] === 'folder') {
                    $this->execute($name, $isGif, $bulkMode, $file['items']);
                } else {
                    $this->ffmpegTask($file, $isGif);
                }
            }
        } else {
            if ($this->console) {
                $this->console->info('generating thumbnail for ' . $name);
            }

            $this->ffmpegTask($name, $isGif);
        }

        if ($this->errors['thumbnails'] > 0) {
            return ['status' => 'error', 'errors' => $this->errors];
        }

        return ['status' => 'success', 'errors' => null];
    }

    /**
     * FFMPEG processing task to generate thumbnails.
     *
     * @param array|string $item
     * @param bool $isGif
     */
    public function ffmpegTask($item, bool $isGif): void
    {
        $video = is_array($item) ? $item['path'] : $this->videoStorageSource($item);

        try {
            if ($isGif) {
                $filename = is_array($item) ? $item['name'] : pathinfo($item)['basename'];
                $filename .= '.gif';

                $storageDestination = $this->thumbnailStorageDestination($filename, $isGif);

                $this->openVideo($video)
                    ->gif(TimeCode::fromSeconds($this->start), new Dimension(500, 250), $this->seconds)
                    ->save($storageDestination);
            } else {
                $filename = is_array($item) ? $item['name'] : pathinfo($item)['basename'];
                $filename .= '.jpg';

                $storageDestination = $this->thumbnailStorageDestination($filename, $isGif);

                $this->openVideo($video)
                    ->frame(TimeCode::fromSeconds($this->start))
                    ->save($storageDestination);
            }

            if ($this->console) {
                $this->console->success('[' . $filename . '] thumbnail created');
            }

            $this->updateMetadataAttribute($video, [
                'thumbnail_filepath' => $this->thumbnailStorageDestination($filename, $isGif),
                'thumbnail_filename' => $filename
            ]);
        } catch (Exception $e) {
            if ($this->console) {
                $this->console->error('[' . $video . '] ' . $e->getMessage());
            }

            ++$this->errors['thumbnails'];
        }
    }
}
