<?php

namespace Thorongil\Syntract\Exception;

/**
 * Class ConfigException
 *
 * @package Thorongil\Syntract\Exception
 */
class ConfigException extends SyntractException
{
    const EXCEPTION_FILE_NOT_FOUND = 'File does not exist: %s';
    const EXCEPTION_KEY_NOT_FOUND = 'Key does not exist: %s';
}