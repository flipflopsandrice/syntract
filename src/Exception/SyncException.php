<?php

namespace Thorongil\Syntract\Exception;

/**
 * Class SyncException
 *
 * @package Thorongil\Syntract\Exception
 */
class SyncException extends SyntractException
{
    const EXCEPTION_RSYNC = 'Rsync threw an exception: %s';
}
