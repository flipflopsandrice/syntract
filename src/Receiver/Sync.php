<?php

namespace Thorongil\Syntract\Receiver;

use AFM\Rsync\Rsync;
use Thorongil\Syntract\Exception\SyncException;
use Thorongil\Syntract\Log;

/**
 * Class Sync
 *
 * @package Thorongil\Syntract\Receiver
 */
class Sync extends AbstractReceiver
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var Rsync
     */
    private $client;

    /**
     * @var Log
     */
    private $log;

    /**
     * Sync constructor.
     *
     * @param array $config
     * @param Log   $log
     */
    public function __construct($config = [], Log $log)
    {
        $this->log    = $log;
        $this->config = $config;
        $this->client = new Rsync($config);
    }

    /**
     * Execute the sync
     *
     * @throws SyncException
     */
    public function sync()
    {
        $this->log->addDebug('SyncCommand:sync', $this->config);

        $origin = $this->config['folders']['remote'];
        $target = $this->config['folders']['local'];

        try {
            $this->client->setArchive(true);
            $this->client->setCompression(true);
            $this->client->sync($origin, $target);
        } catch (\Exception $e) {
            throw new SyncException($e->getMessage(), 0, $e);
        }
    }
}
