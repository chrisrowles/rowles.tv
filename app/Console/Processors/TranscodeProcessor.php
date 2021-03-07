<?php

namespace Rowles\Console\Processors;

use Log;
use Exception;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\Dimension;
use Rowles\Console\Interfaces\TranscodeProcessorInterface;

class TranscodeProcessor extends BaseProcessor implements TranscodeProcessorInterface
{
    /** @var array $errors */
    protected array $errors = ['videos' => 0];

    /** @var int $kiloBitrate */
    protected int $kiloBitrate = 1000;

    /** @var int $audioChannels */
    protected int $audioChannels = 2;

    /** @var int $audioKiloBitrate */
    protected int $audioKiloBitrate = 256;

    /** @var int $constantRateFactor */
    protected int $constantRateFactor = 20;

    /**
     * TranscodeProcessor constructor.
     *
     * @param bool $console
     */
    public function __construct($console = false)
    {
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
     * @param array $opts
     * @param array $recursiveData
     * @return array
     * @throws Exception
     */
    public function execute($name = null, $ext = null, array $opts = [], array $recursiveData = []): array
    {
        // Disallow directory traversal
        if (substr($name, 0, 2) === '..') return ['status' => 'error', 'errors' => ['transcode' => 1]];

        if (!$name && $opts['bulk']) {
            // Scan video storage, unless we already have and we're processing files in folders recursively.
            $scan = empty($recursiveData) ? $this->getVideosFromStorage() : $recursiveData;

            if ($this->console && is_integer($scan['total'])) {
                $this->console->info($scan['total'] . ' videos to transcode');
            }

            foreach ($scan['items'] as $file) {
                if ($file['type'] === 'folder') {
                    // If we've found a folder, then repeat this process with the folder's items.
                    $this->execute($name, $ext, $opts, $file['items']);
                } else {
                    // If we've found a file, then run the transcoding task.
                    $this->ffmpegTask($file, $ext ?? $file['extension'], $opts);
                }
            }
        } elseif ($name && !$opts['bulk']) {
            $this->ffmpegTask($name, $ext ?? pathinfo($name)['extension'], $opts);
        } else {
            Log::error(var_export(['process' => 'transcode', 'filename' => $name, 'options' => $opts]));
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
     * @param array $opts
     * @return void
     */
    public function ffmpegTask($item, string $ext, array $opts) : void
    {
        if (is_array($item) && isset($item['path'])) {
            // If we're bulk processing, then pass the bulk processing format
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

            if ($opts['clip']['enable']) {
                $from = isset($opts['clip']['from']) ? $opts['clip']['from'] : 45;
                $to = isset($opts['clip']['to']) ? $opts['clip']['to'] : 10;

                if ($this->console) {
                    $this->console->info('clip at ' . gmdate('H:i:s', $from) . ' for ' . $to . ' seconds');
                }

                $media->filters()->clip(TimeCode::fromSeconds($from), TimeCode::fromSeconds($to));
            }

            if (isset($opts['resize'])) {
                $width = isset($opts['resize']['width']) ? $opts['resize']['width'] : 500;
                $height = isset($opts['resize']['height']) ? $opts['resize']['height'] : 250;

                if ($this->console) {
                    $this->console->info('resize to ' . $width . 'x' . $height);
                }

                $media->filters()->resize(new Dimension($width, $height));
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

            $format->setKiloBitrate($this->kiloBitrate)
                ->setAudioChannels($this->audioChannels)
                ->setAudioKiloBitrate($this->audioKiloBitrate);

            $format->setAdditionalParameters(['-crf', $this->constantRateFactor]);
            $filename = $this->videoStorageDestination(pathinfo($video)['filename']) . '.' . $ext;

            $media->save($format, $filename);
        } catch (Exception $e) {
            if ($this->console) {
                $this->console->error("[" . $video . "] " . $e->getMessage() . "\n\n");
            }

            ++$this->errors['videos'];
        }
    }

    /**
     * @param int $kiloBitrate
     * @return self
     */
    public function setKiloBitrate(int $kiloBitrate): self
    {
        $this->kiloBitrate = $kiloBitrate;
        return $this;
    }

    /**
     * @param int $audioChannels
     * @return self
     */
    public function setAudioChannels(int $audioChannels): self
    {
        $this->audioChannels = $audioChannels;
        return $this;
    }

    /**
     * @param int $audioKiloBitrate
     * @return self
     */
    public function setAudioKiloBitrate(int $audioKiloBitrate): self
    {
        $this->audioKiloBitrate = $audioKiloBitrate;
        return $this;
    }

    /**
     * @param int $constantRateFactor
     * @return self
     */
    public function setConstantRateFactor(int $constantRateFactor): self
    {
        $this->constantRateFactor = $constantRateFactor;
        return $this;
    }
}
