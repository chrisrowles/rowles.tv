<?php

namespace Rowles\Console\Interfaces;

use Exception;

interface TranscodeProcessorInterface extends BaseProcessorInterface
{
    /**
     * @param mixed $name
     * @param mixed $ext
     * @param array $recursiveData
     * @return array
     * @throws Exception
     */
    public function execute($name = null, $ext = null, array $recursiveData = []): array;

    /**
     * @param mixed $item
     * @param string $ext
     * @return void
     */
    public function ffmpegTask($item, string $ext) : void;

    /**
     * @param int $kiloBitrate
     * @return self
     */
    public function setKiloBitrate(int $kiloBitrate): self;

    /**
     * @param int $audioChannels
     * @return self
     */
    public function setAudioChannels(int $audioChannels): self;

    /**
     * @param int $audioKiloBitrate
     * @return self
     */
    public function setAudioKiloBitrate(int $audioKiloBitrate): self;

    /**
     * @param int $constantRateFactor
     * @return self
     */
    public function setConstantRateFactor(int $constantRateFactor): self;

    /**
     * @param array $options
     * @return $this
     */
    public function mapOptions(array $options): self;
}
