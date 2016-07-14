<?php

namespace Thorongil\Syntract\Command;

/**
 * Class Extract
 *
 * @package Thorongil\Syntract\Command
 */
class Extract extends AbstractCommand
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->log->addInfo('Getting queue');

        $this->log->addDebug('Getting queue');
        $queue = $this->receiver->getQueue();

        $this->log->addDebug('Starting extract', ['queue' => $queue]);
        $extracted = $this->receiver->extract($queue);

        $this->log->addInfo('Executing clean');
        $this->receiver->clean($extracted);

        $this->log->addInfo('Extract completed');
    }
}
