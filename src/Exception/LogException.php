<?php

namespace Thorongil\Syntract\Exception;

/**
 * Class LogException
 *
 * @package Thorongil\Syntract\Exception
 */
class LogException extends SyntractException
{
    const EXCEPTION_MISSING       = 'Log configuration is missing';
    const EXCEPTION_NAME_MISSING  = 'Name is missing';
    const EXCEPTION_FILE_MISSING  = 'File is missing';
    const EXCEPTION_LEVEL_MISSING = 'Level is missing';
}