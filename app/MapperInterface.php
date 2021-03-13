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
     * @param string $command
     * @return mixed
     */
    public function getCommand(string $namespace, string $command);

    /**
     * @return array
     */
    public function getAvailableCommands() : array;
}
