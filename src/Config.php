<?php

namespace Thorongil\Syntract;

use Symfony\Component\Yaml\Yaml;
use Thorongil\Syntract\Exception\ConfigException;

/**
 * Class Config
 *
 * @package Thorongil\Syntract
 */
class Config
{
    /**
     * @var array
     */
    private $config;

    /**
     * Config constructor.
     *
     * @param string $file
     *
     * @throws ConfigException
     */
    public function __construct($file = '')
    {
        if (!file_exists($file)) {
            throw new ConfigException(ConfigException::EXCEPTION_FILE_NOT_FOUND, [$file]);
        }
        $this->loadConfigFromFile($file);
    }

    /**
     * @return array
     *
     * @throws ConfigException
     */
    public function get($key='')
    {
        if (!isset($this->config[$key])) {
            throw new ConfigException(ConfigException::EXCEPTION_KEY_NOT_FOUND, $key);
        }

        return $this->config[$key];
    }

    /**
     * Load configuration from file
     *
     * @param $file
     *
     * @throws ConfigException
     */
    private function loadConfigFromFile($file)
    {
        $yaml   = new Yaml();
        $config = $yaml->parse(file_get_contents($file));

        $this->config = $config;
    }
}
