<?php

namespace Rowles\Console\Interfaces;

interface ProcessingTaskInterface extends BaseProcessorInterface
{
    /**
     * @param mixed $item
     * @return void
     */
    public function ffmpegTask($item): void;
}
