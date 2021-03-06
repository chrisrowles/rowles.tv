<?php

namespace Rowles\Console\Processors;

use Rowles\Models\Video;
use Rowles\Models\Metadata;
use FFMpeg\Exception\InvalidArgumentException;
use Rowles\Console\Interfaces\MetadataProcessorInterface;

class MetadataProcessor extends BaseProcessor implements MetadataProcessorInterface
{
    /** @var array $errors */
    protected array $errors = ['metadata' => 0];

    /**
     * Map Metadata model properties to MetadataProcessor methods.
     *
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
        parent::__construct($console);
    }

    /**
     * Recursive method to extract video metadata.
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
    public function execute(string $name = "", bool $bulkMode = false, $recursiveMode = []): array
    {
        if ($bulkMode) {
            $scan = empty($recursiveMode) ? $this->getVideosFromStorage() : $recursiveMode;

            if ($this->console && is_integer($scan['total'])) {
                $this->console->info($scan['total'] . ' videos to extract metadata from');
            }

            foreach ($scan['items'] as $file) {
                if ($file['type'] === 'folder') {
                    $this->execute($name,true, $file['items']);
                } else {
                    $this->ffmpegTask($file);
                }
            }
        } else {
            if ($this->console) {
                $this->console->info('extracting metadata from ' . $name);
            }

            $this->ffmpegTask($name);
        }

        if ($this->errors['metadata'] > 0) {
            return ['status' => 'error', 'errors' => $this->errors];
        }

        return ['status' => 'success', 'errors' => null];
    }

    /**
     * FFMPEG processing task to extract metadata.
     *
     * @param array|string $item
     * @return void
     */
    public function ffmpegTask($item): void
    {
        $video = is_array($item) ? $item['path'] : $this->videoStorageSource($item);
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
