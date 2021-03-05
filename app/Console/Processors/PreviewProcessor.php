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
     * @param bool $console
     */
    public function __construct($console = false)
    {
        parent::__construct($console);
    }

    /**
     * @param string $name
     * @param bool $bulkMode
     * @return array
     */
    public function execute(string $name = "", bool $bulkMode = false): array
    {
        if ($bulkMode) {
            $files = $this->getVideosFromStorage();
            foreach ($files as $file) {
                $this->ffmpegTask($file['name']);
            }
        } else {
            if (!file_exists($this->previewStorageDestination($name))) {
                $this->ffmpegTask($name);
            }
        }

        if ($this->errors['previews'] > 0) {
            return ['status' => 'error', 'errors' => $this->errors];
        }

        return ['status' => 'success', 'errors' => null];
    }

    /**
     * @param string $name
     * @return void
     */
    public function ffmpegTask(string $name): void
    {
        try {
            $media = $this->openVideo($this->videoStorageSource($name));
            $media->filters()->clip(TimeCode::fromSeconds($this->start), TimeCode::fromSeconds($this->seconds));
            $media->save($this->getNewFormat(), $this->previewStorageDestination($name));

            if ($this->console) {
                $this->console->success('[' . $name . '] preview created');
            }

            $this->updateMetadataAttribute($name, 'preview', $this->previewStorageDestination($name));
        } catch (Exception $e) {
            if ($this->console) {
                $this->console->error('[' . $name . '] ' . $e->getMessage());
            }

            ++$this->errors['previews'];
        }
    }
}
