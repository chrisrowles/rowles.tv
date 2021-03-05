<?php

namespace Rowles\Console\Processors;

use Exception;
USE Rowles\Models\Metadata;
use FFMpeg\Coordinate\{Dimension, TimeCode};
use Rowles\Console\Interfaces\ThumbnailProcessorInterface;

class ThumbnailProcessor extends BaseProcessor implements ThumbnailProcessorInterface
{
    /** @var array $errors */
    protected array $errors = ['thumbnails' => 0];

    /**
     * ThumbnailProcessor constructor.
     * @param bool $console
     */
    public function __construct($console = false)
    {
        parent::__construct($console);
    }

    /**
     * @param string $name
     * @param bool $isGif
     * @param bool $bulkMode
     * @return array
     */
    public function execute(string $name = "", bool $isGif = false, bool $bulkMode = false): array
    {
        if ($bulkMode) {
            $scan = $this->getVideosFromStorage();

            if ($this->console) {
                $this->console->info($scan['total']['files'] . ' thumbnails to generate');
            }

            foreach ($scan['items'] as $file) {
                $this->ffmpegTask($file['name'], $isGif);
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
     * @param string $name
     * @param bool $isGif
     */
    public function ffmpegTask(string $name, bool $isGif): void
    {
        try {
            if ($isGif) {
                $filename = $name.'.gif';
                $storageDestination = $this->thumbnailStorageDestination($filename, $isGif);

                $this->openVideo($this->videoStorageSource($name))
                    ->gif(TimeCode::fromSeconds($this->start), new Dimension(500, 250), $this->seconds)
                    ->save($storageDestination);
            } else {
                $filename = $name.'.jpg';
                $storageDestination = $this->thumbnailStorageDestination($filename, $isGif);

                $this->openVideo($this->videoStorageSource($name))
                    ->frame(TimeCode::fromSeconds($this->start))
                    ->save($storageDestination);
            }

            if ($this->console) {
                $this->console->success('[' . $filename . '] thumbnail created');
            }

            $this->updateMetadataAttribute($name, [
                'thumbnail_filepath' => $this->thumbnailStorageDestination($filename, $isGif),
                'thumbnail_filename' => $filename
            ]);
        } catch (Exception $e) {
            if ($this->console) {
                $this->console->error('[' . $name . '] ' . $e->getMessage());
            }

            ++$this->errors['thumbnails'];
        }
    }
}
