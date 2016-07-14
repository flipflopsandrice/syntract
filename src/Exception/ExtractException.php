<?php

namespace Thorongil\Syntract\Exception;

/**
 * Class ExtractException
 *
 * @package Thorongil\Syntract\Exception
 */
class ExtractException extends SyntractException
{
    const EXCEPTION_SETTING_MISSING = 'Setting is missing: %s';
    const EXCEPTION_INVALID_FOLDER  = 'Invalid folder: %s';
}
