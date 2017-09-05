<?php

require_once __DIR__ . '/../bootstrap/constants.php';

// get config
$config = \Noodlehaus\Config::load(__DIR__ . '/../config');

// build logger
$logFormatter = (PHP_SAPI === 'cli')
    ? new \Monolog\Formatter\LineFormatter('%message% ' . PHP_EOL)
    : new \Monolog\Formatter\LineFormatter('%message% <br/>');

$logHandler = (new \Monolog\Handler\StreamHandler('php://output'))->setFormatter($logFormatter);

$logger = (new \Monolog\Logger('logger'))
    ->pushHandler($logHandler)
    ->pushProcessor(new \Monolog\Processor\PsrLogMessageProcessor());

// build and bootstrap the application
$game = new \App\Game($config, $logger);
$game->bootstrap();

return $game;
