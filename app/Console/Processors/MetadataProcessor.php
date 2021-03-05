<?php

namespace Rowles\Console\Processors;

use Rowles\Models\Video;
use Rowles\Models\Metadata;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Exception\InvalidArgumentException;
use Rowles\Console\Interfaces\MetadataProcessorInterface;

class MetadataProcessor extends BaseProcessor implements MetadataProcessorInterface
{
    /** @var array $errors */
    protected array $errors = ['metadata' => 0];

    /** @var array $mappings */
    protected static array $mappings = [
        'filesize' => 'getFilesize',
        'format' => 'getFormat',
        'codec' => 'getCodec',
        'bitrate' => 'getBitrate',
        'duration' => 'getDuration'
    ];

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
            $this->ffmpegTask($name);
        }

        if ($this->errors['metadata'] > 0) {
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
        $video = Video::where('filepath', $this->videoStorageSource($name))->first();

        if ($video && !Metadata::where('video_id', $video->id)->exists()) {
            $metadata = new Metadata();
            $metadata->video_id = $video->id;
            foreach ($metadata->getFillable() as $attribute) {
                try {
                    $metadata->{$attribute} = $this->{static::$mappings[$attribute]}($video);
                    if ($this->console) {
                        $this->console->success('[' . $name . '] - ' . $attribute . ' extracted');
                    }
                } catch(InvalidArgumentException $e) {
                    if ($this->console) {
                        $this->console->error('[' . $name . '] - ' . $e->getMessage());
                    }

                    ++$this->errors['metadata'];
                }
            }

            if ($metadata->save()) {
                if ($this->console) {
                    $this->console->success('[' . $name . '] - Metadata record created');
                }
            } else {
                ++$this->errors['metadata'];
            }
        }
    }

    /**
     * @param Video $video
     * @return int
     */
    public function getFileSize(Video $video): int
    {
        return (int)filesize($this->videoStorageSource($video->filename));
    }

    /**
     * @param Video $video
     * @return string
     * @throws InvalidArgumentException
     */
    public function getFormat(Video $video): string
    {
        return (string)$this->openVideo($this->videoStorageSource($video->filename))
            ->getFormat()
            ->get('format_name');
    }

    /**
     * @param Video $video
     * @return string
     * @throws InvalidArgumentException
     */
    public function getCodec(Video $video): string
    {
        return (string)$this->openVideo($this->videoStorageSource($video->filename))
            ->getStreams()
            ->videos()
            ->first()
            ->get('codec_name');
    }

    /**
     * @param Video $video
     * @return int
     * @throws InvalidArgumentException
     */
    public function getBitrate(Video $video): int
    {
        return (int)$this->openVideo($this->videoStorageSource($video->filename))
            ->getFormat()
            ->get('bit_rate');
    }

    /**
     * @param Video $video
     * @return float
     * @throws InvalidArgumentException
     */
    public function getDuration(Video $video): float
    {
        return (float)$this->openVideo($this->videoStorageSource($video->filename))
            ->getStreams()
            ->videos()
            ->first()
            ->get('duration');
    }
}
