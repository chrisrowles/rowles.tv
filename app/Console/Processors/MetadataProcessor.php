<?php

namespace Rowles\Console\Processors;

use Rowles\Models\Video;
use Rowles\Models\Metadata;
use FFMpeg\Exception\InvalidArgumentException;

class MetadataProcessor extends BaseProcessor implements ProcessingTaskInterface
{
    /** @var array $errors */
    protected array $errors = ['metadata' => 0];

    /** @var array  */
    protected array $options;

    /** @var string  */
    public string $identifier = 'metadata';

    /**
     * @var array $mappings
     */
    protected static array $mappings = [
        'filesize' => 'getFilesize',
        'format' => 'getFormat',
        'codec' => 'getCodec',
        'bitrate' => 'getBitrate',
        'duration' => 'getDuration'
    ];

    /**
     * MetadataProcessor constructor.
     *
     * @param bool $console
     */
    public function __construct($console = false)
    {
        $this->options = [
            'bulk' => false
        ];

        parent::__construct($console);
    }

    /**
     * FFMPEG processing task to extract metadata.
     *
     * @param array|string $item
     * @return void
     */
    public function ffmpegTask($item): void
    {
        if (is_array($item) && isset($item['path'])) {
            // If we are bulk processing, then pass the bulk processing format
            $video = $item['path'];
        } else {
            // Otherwise just fetch the single file
            $video = $this->videoStorageSource($item);
        }

        $record = Video::where('filepath', $video)->first();

        if ($record && !Metadata::where('video_id', $record->id)->exists()) {
            $metadata = new Metadata();
            $metadata->video_id = $record->id;
            foreach ($metadata->getFillable() as $attribute) {
                try {
                    $metadata->{$attribute} = $this->{static::$mappings[$attribute]}($record->filepath);
                } catch(InvalidArgumentException $e) {
                    if ($this->console) {
                        $this->console->error('[' . $video . '] - ' . $e->getMessage());
                    }

                    ++$this->errors['metadata'];
                }
            }

            if ($metadata->save()) {
                if ($this->console) {
                    $this->console->success('[' . $video . '] - metadata record created');
                }
            } else {
                if ($this->console) {
                    $this->console->error('[' . $video . '] - failed to save metadata record');
                }

                ++$this->errors['metadata'];
            }

        } else {
            if ($video) {
                $this->console->info('[' . $video . '] - metadata record already exists.');
            } else {
                $this->console->error('[' . $video . '] - video not imported');
                ++$this->errors['metadata'];
            }
        }
    }

    /**
     * @param string $path
     * @return int
     */
    public function getFileSize(string $path): int
    {
        return (int)filesize($path);
    }

    /**
     * @param string $path
     * @return string
     * @throws InvalidArgumentException
     */
    public function getFormat(string $path): string
    {
        return (string)$this->openVideo($path)
            ->getFormat()
            ->get('format_name');
    }

    /**
     * @param string $path
     * @return string
     * @throws InvalidArgumentException
     */
    public function getCodec(string $path): string
    {
        return (string)$this->openVideo($path)
            ->getStreams()
            ->videos()
            ->first()
            ->get('codec_name');
    }

    /**
     * @param string $path
     * @return int
     * @throws InvalidArgumentException
     */
    public function getBitrate(string $path): int
    {
        return (int)$this->openVideo($path)
            ->getFormat()
            ->get('bit_rate');
    }

    /**
     * @param string $path
     * @return float
     * @throws InvalidArgumentException
     */
    public function getDuration(string $path): float
    {
        return (float)$this->openVideo($path)
            ->getStreams()
            ->videos()
            ->first()
            ->get('duration');
    }
}
