<?php

namespace SFramework\Config;

/**
 * Interface ConfigInterface
 *
 * @package SFramework\Config
 */
interface ConfigInterface
{
    /**
     * Loads a config file.
     *
     * @return array
     */
    public function load();
}