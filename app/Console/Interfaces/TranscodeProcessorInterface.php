<?php

namespace Rowles\Console\Interfaces;

interface TranscodeProcessorInterface extends BaseProcessorInterface
{
    /**
     * @param string $name
     * @param string $ext
     * @return array
     */
    public function execute(string $name, string $ext = 'mp4'): array;

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
}
