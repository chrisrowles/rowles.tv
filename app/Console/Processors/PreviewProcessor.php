<?php

namespace Rowles\Console\Processors;

use Exception;
use FFMpeg\Coordinate\TimeCode;
use Rowles\Console\Interfaces\PreviewProcessorInterface;

class PreviewProcessor extends BaseProcessor implements PreviewProcessorInterface
{
    /** @var array $errors */
    protected array $errors = ['previews' => 0];

    /**
     * PreviewProcessor constructor.
     *
     * @param bool $console
     */
    public function __construct($console = false)
    {
        parent::__construct($console);
    }

    /**
     * Recursive method to generate video preview files.
     *
     * Single
     * Simply passes the filename to fetch from app storage.
     *
     * Bulk Mode
     * $recursiveMode will be empty upon first scan, if folders are found during the first scan, this method is
     * called again with $recursiveMode populated with the folders items, this process repeats until no more
     * sub-folders are left.
     *
     * @param string $name
     * @param bool $bulkMode
     * @param array $recursiveMode
     * @return array
     */
    public function execute(string $name = "", bool $bulkMode = false, array $recursiveMode = []): array
    {
        if ($bulkMode) {
            $scan = empty($recursiveMode) ? $this->getVideosFromStorage() : $recursiveMode;

            if ($this->console && is_integer($scan['total'])) {
                $this->console->info($scan['total'] . ' previews to generate');
            }

            foreach ($scan['items'] as $file) {
                if ($file['type'] === 'folder') {
                    $this->execute($name, $bulkMode, $file['items']);
                } else {
                    $this->ffmpegTask($file);
                }
            }
        } else {
            if ($this->console) {
                $this->console->info('generating preview for ' . $name);
            }

            $this->ffmpegTask($name);
        }

        if ($this->errors['previews'] > 0) {
            return ['status' => 'error', 'errors' => $this->errors];
        }

        return ['status' => 'success', 'errors' => null];
    }

    /**
     * FFMPEG processing task to generate previews.
     *
     * @param array|string $item
     * @return void
     */
    public function ffmpegTask($item): void
    {
        $video = is_array($item) ? $item['path'] : $this->videoStorageSource($item);

        try {
            $media = $this->openVideo($video);
            $media->filters()->clip(TimeCode::fromSeconds($this->start), TimeCode::fromSeconds($this->seconds));
            $media->save($this->getNewFormat(), $this->previewStorageDestination(pathinfo($video)['basename']));

            if ($this->console) {
                $this->console->success('[' . $video . '] preview created');
            }

            $this->updateMetadataAttribute($video, [
                'preview_filepath' => $this->previewStorageDestination($video),
                'preview_filename' => $video
            ]);
        } catch (Exception $e) {
            if ($this->console) {
                $this->console->error('[' . $video . '] ' . $e->getMessage());
            }

            ++$this->errors['previews'];
        }
    }
}
