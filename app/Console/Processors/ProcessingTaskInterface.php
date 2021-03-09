<?php

namespace Rowles\Console\Processors;

interface ProcessingTaskInterface extends BaseProcessorInterface
{
    /**
     * @param mixed $item
     * @return void
     */
    public function ffmpegTask($item): void;
}
