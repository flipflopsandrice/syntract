<?php

namespace Thorongil\Syntract\Command;

/**
 * Class Sync
 *
 * @package Thorongil\Syntract\Command
 */
class Sync extends AbstractCommand
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->log->addInfo('Executing sync');
        $this->receiver->sync();
        $this->log->addInfo('Sync completed');
    }
}
