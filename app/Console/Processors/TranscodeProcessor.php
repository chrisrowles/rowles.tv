<?php

namespace Rowles\Console\Processors;

use Log;
use Exception;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\Dimension;
use Rowles\Console\Interfaces\TranscodeProcessorInterface;

class TranscodeProcessor extends BaseProcessor implements TranscodeProcessorInterface
{
    /** @var array */
    protected array $errors = ['videos' => 0];

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
            'constant-rate-factor' => 20
        ];

        parent::__construct($console);
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
     * @param mixed $ext
     * @param array $recursiveData
     * @return array
     * @throws Exception
     */
    public function execute($name = null, $ext = null, array $recursiveData = []): array
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
                    $this->execute($name, $ext, $file['items']);
                } else {
                    // If we have found a file, then run the transcoding task.
                    $this->ffmpegTask($file, $ext ?? $file['extension']);
                }
            }
        } elseif ($name && !$this->options['bulk']) {
            $this->ffmpegTask($name, $ext ?? pathinfo($name)['extension']);
        } else {
            Log::error(var_export(['process' => 'transcode', 'filename' => $name, 'options' => $this->options]));
            throw new Exception('You must provide valid options, please check the logs for more information.');
        }

        if ($this->errors['videos'] > 0) {
            return ['status' => 'error', 'errors' => $this->errors];
        }

        return ['status' => 'success', 'errors' => null];
    }

    /**
     * FFMPEG processing task to transcode videos.
     *
     * @param mixed $item
     * @param string $ext
     * @return void
     */
    public function ffmpegTask($item, string $ext) : void
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

            $format->setKiloBitrate($this->options['bitrate'])
                ->setAudioChannels($this->options['audio-channels'])
                ->setAudioKiloBitrate($this->options['audio-bitrate']);

            $format->setAdditionalParameters(['-crf', $this->options['constant-rate-factor']]);
            $filename = $this->videoStorageDestination(pathinfo($video)['filename']) . '.' . $ext;

            $media->save($format, $filename);
        } catch (Exception $e) {
            if ($this->console) {
                $this->console->error("[" . $video . "] " . $e->getMessage() . "\n\n");
            }

            ++$this->errors['videos'];
        }
    }
}
