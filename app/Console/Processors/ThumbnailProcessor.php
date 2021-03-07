<?php

namespace Rowles\Console\Processors;

use Exception;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\Dimension;
use Rowles\Console\Interfaces\ProcessingTaskInterface;

class ThumbnailProcessor extends BaseProcessor implements ProcessingTaskInterface
{
    /** @var array */
    protected array $errors = ['thumbnails' => 0];

    /** @var array  */
    protected array $options;

    /** @var string  */
    public string $identifier = 'thumbnails';

    /**
     * TranscodeProcessor constructor.
     *
     * @param bool $console
     */
    public function __construct($console = false)
    {
        $this->options = [
            'bulk' => false,
            'from' => 40,
            'gif' => [
                'enable' => false,
                'duration' => 5
            ]
        ];

        parent::__construct($console);
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
                    TimeCode::fromSeconds($this->options['from']),
                    new Dimension(500, 250),
                    $this->options['gif']['duration']
                )->save($storageDestination);
            } else {
                $filename .= '.jpg';
                $storageDestination = $this->thumbnailStorageDestination($filename);
                $media->frame(TimeCode::fromSeconds($this->options['from']))->save($storageDestination);
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
