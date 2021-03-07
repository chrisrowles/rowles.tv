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
}
