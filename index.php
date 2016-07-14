<?php

require_once "vendor/autoload.php";

$flags   = new donatj\Flags();
$command = &$flags->string('execute', false, 'Execute command <sync|extract>');

try {
    $flags->parse();
} catch (Exception $e) {
    die($e->getMessage() . PHP_EOL . $flags->getDefaults() . PHP_EOL);
}

$config  = new \Thorongil\Syntract\Config(__DIR__ . '/config/config.yml');
$invoker = new \Thorongil\Syntract\Invoker($config);
$invoker->run($command);
