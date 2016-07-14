<?php

namespace Thorongil\Syntract;

use Thorongil\Syntract\Command\Extract as ExtractCommand;
use Thorongil\Syntract\Command\Sync as SyncCommand;
use Thorongil\Syntract\Exception\ExtractException;
use Thorongil\Syntract\Exception\InvokerCommandException;
use Thorongil\Syntract\Exception\InvokerException;
use Thorongil\Syntract\Exception\InvokerPidException;
use Thorongil\Syntract\Receiver\Extract as ExtractReceiver;
use Thorongil\Syntract\Receiver\Sync as SyncReceiver;

/**
 * Class Invoker
 *
 * @package Thorongil\Syntract
 */
class Invoker
{
    /** Class constants */
    const COMMAND_SYNC    = 'sync';
    const COMMAND_EXTRACT = 'extract';
    const PID_FILE        = '/tmp/syntract.pid';
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Log
     */
    private $log;

    /** @var resource */
    private $pid;

    /**
     * Syntract constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->log    = new Log($config->get('log'));
    }

    /**
     * Invoke the command
     *
     * @param string $command
     */
    public function run($command)
    {
        $this->lock(true);
        $this->log->addInfo('Run started', ['command' => $command]);

        try {
            sleep(5);
            switch ($command) {
                case self::COMMAND_SYNC:
                    $this->executeSyncCommand();
                    break;
                case self::COMMAND_EXTRACT:
                    $this->executeExtractCommand();
                    break;
                default:
                    throw new InvokerCommandException(InvokerCommandException::EXCEPTION_UNKNOWN_COMMAND);
            }
        } catch (InvokerCommandException $e) {
            echo sprintf('Unknown command: %s' . "\n", $command);
        } catch (\Exception $e) {
            $this->log->addError($e->getMessage(), ['exception' => $e]);
        }

        $this->log->addInfo('Run completed');
        $this->lock(false);
    }

    /**
     * @throws \Exception
     */
    private function executeSyncCommand()
    {
        $syncReceiver = new SyncReceiver($this->config->get('sync'), $this->log);
        $syncCommand  = new SyncCommand($syncReceiver, $this->log);
        $syncCommand->execute();
    }

    /**
     * @throws \Exception
     */
    private function executeExtractCommand()
    {
        $extractReceiver = new ExtractReceiver($this->config->get('extract'), $this->log);
        $extractCommand  = new ExtractCommand($extractReceiver, $this->log);
        $extractCommand->execute();
    }

    /**
     * @param bool $set
     *
     * @todo refactor into a FileLock lib
     */
    private function lock($set=true)
    {
        if ($set === true) {
            $this->pid = fopen(self::PID_FILE, "ab+");
            if (flock($this->pid, LOCK_EX | LOCK_NB)) {
                ftruncate($this->pid, 0);
            } else {
                $this->log->addNotice('PID locked');
                echo 'PID locked'."\n";
                exit(0);
            }
        } else {
            flock($this->pid, LOCK_UN);
            fclose($this->pid);
        }
    }
}
