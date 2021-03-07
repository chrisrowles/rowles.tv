<?php

namespace Rowles\Console\Processors;

use Exception;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\Dimension;
use Rowles\Console\Interfaces\ProcessingTaskInterface;

class TranscodeProcessor extends BaseProcessor implements ProcessingTaskInterface
{
    /** @var array */
    protected array $errors = ['videos' => 0];

    /** @var array  */
    protected array $options;

    /** @var string  */
    public string $identifier = 'videos';

    /**
     * TranscodeProcessor constructor.
     *
     * @param bool $console
     */
    public function __construct($console = false)
    {
        $this->options = [
            'bulk' => false,
            'ext' => 'mp4',
            'clip' => [
                'enable' => false,
                'from' => 40,
                'duration' => 5
            ],
            'resize' => [
                'enable' => false,
                'width' => 500,
                'height' => 250
            ],
            'bitrate' => 1000,
            'audio-bitrate' => 256,
            'audio-channels' => 2,
            'constant-rate-factor' => 20,
            'is-preview' => false
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
            $ext = $this->options['ext'] ?? pathinfo($video, PATHINFO_EXTENSION);

            if ($this->console) {
                $this->console->info('transcoding ' . $video . ' to ' . $ext);
            }

            if ($this->options['clip']['enable']) {
                if ($this->console) {
                    $this->console->info('clip at ' . gmdate('H:i:s', $this->options['clip']['from']) .
                        ' for ' . $this->options['clip']['duration'] . ' seconds');
                }

                $media->filters()->clip(
                    TimeCode::fromSeconds($this->options['clip']['from']),
                    TimeCode::fromSeconds($this->options['clip']['duration'])
                );
            }

            if ($this->options['resize']['enable']) {
                if ($this->console) {
                    $this->console->info('resize to ' . $this->options['resize']['width'] . 'x' .
                        $this->options['resize']['height']);
                }

                $media->filters()->resize(
                    new Dimension($this->options['resize']['width'], $this->options['resize']['height'])
                );
            }

            $format = $this->getNewFormat($ext);

            if ($this->console) {
                $format->on('progress', function ($video, $format, $percentage) {
                    if ($video && $format) {
                        if ((int) $percentage > 99) {
                            $this->console->success($percentage . "% complete\n\n");
                        } else {
                            $this->console->info($percentage . '% complete');
                        }
                    }
                });
            }

            $format->setKiloBitrate((int)$this->options['bitrate'])
                ->setAudioChannels((int)$this->options['audio-channels'])
                ->setAudioKiloBitrate((int)$this->options['audio-bitrate']);
            $format->setAdditionalParameters(['-crf', (int)$this->options['constant-rate-factor']]);

            if ($this->options['is-preview']) {
                $filepath = $this->previewStorageDestination(pathinfo($video)['filename']);
            } else {
                $filepath = $this->videoStorageDestination(pathinfo($video)['filename']);
            }

            $filepath .= '.' . $ext;
            $media->save($format, $filepath);

            if ($this->options['is-preview']) {
                $this->updateMetadataAttribute($video, [
                    'preview_filepath' => $this->previewStorageDestination(""),
                    'preview_filename' => pathinfo($video)['filename'] . '.' . $ext
                ]);
            }
        } catch (Exception $e) {
            if ($this->console) {
                $this->console->error("[" . $video . "] " . $e->getMessage() . "\n\n");
            }

            ++$this->errors['videos'];
        }
    }
}
