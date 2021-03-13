<?php

namespace Rowles;

interface MapperInterface
{
    /**
     * @param string $command
     * @param array $options
     * @return string
     */
    public function execute(string $command, array $options = []) : string;

    /**
     * @param string $namespace
     * @param string $signature
     * @return mixed
     */
    public function map(string $namespace, string $signature);

    /**
     * @return array
     */
    public function getAvailable() : array;
}
