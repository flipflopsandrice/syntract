<?php

namespace Thorongil\Syntract\Exception;

/**
 * Class SyntractException
 *
 * @package Thorongil\Syntract\Exception
 */
abstract class SyntractException extends \Exception
{
    /**
     * SyntractException constructor.
     *
     * @param string          $message
     * @param array           $values
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($message='', $values=[], $code=0, \Exception $previous=null)
    {
        parent::__construct(vsprintf($message, $values), $code, $previous);
    }
}