<?php

namespace Thorongil\Syntract\Command;

use Thorongil\Syntract\Log;
use Thorongil\Syntract\Receiver\AbstractReceiver;

/**
 * Class AbstractCommand
 *
 * @package Thorongil\Syntract\Command
 */
abstract class AbstractCommand
{
    /**
     * @var Log
     */
    protected $log;

    /**
     * @var AbstractReceiver
     */
    protected $receiver;

    /**
     * AbstractCommand constructor.
     *
     * @param AbstractReceiver $receiver
     * @param Log              $log
     */
    public function __construct(AbstractReceiver $receiver, Log $log)
    {
        $this->receiver = $receiver;
        $this->log      = $log;
    }

    /**
     * @return mixed
     */
    abstract public function execute();
}
