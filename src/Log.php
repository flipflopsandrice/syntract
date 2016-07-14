<?php

namespace Thorongil\Syntract;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Thorongil\Syntract\Exception\LogException;

/**
 * Class Log
 *
 * @package Thorongil\Syntract
 */
class Log extends Logger
{
    /**
     * Log constructor.
     *
     * @param array $config
     *
     * @throws LogException
     */
    public function __construct($config = [])
    {
        /* Set logger name */
        if (!isset($config['name'])) {
            throw new LogException(LogException::EXCEPTION_NAME_MISSING);
        }
        parent::__construct($config['name']);

        /* Set logger file target */
        if (!isset($config['file'])) {
            throw new LogException(LogException::EXCEPTION_FILE_MISSING);
        }
        $file = $config['file'];

        /* Set logger level */
        if (!isset($config['level'])) {
            throw new LogException(LogException::EXCEPTION_LEVEL_MISSING);
        }
        $level = $config['level'];

        /* Initialize handler */
        $this->initHandler($file, $level);
    }

    /**
     * Initializes the streamhandler
     *
     * @param $file
     * @param $level
     */
    private function initHandler($file, $level)
    {
        $intLevel = array_search(strtoupper($level), self::$levels);
        $handler  = new StreamHandler($file, $intLevel);

        $this->pushHandler($handler);
    }
}