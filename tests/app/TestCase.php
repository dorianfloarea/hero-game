<?php

namespace Tests\App;

use App\Game;
use Mockery\Mock;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Noodlehaus\Config;

/**
 * Class TestCase
 * @package Tests\App
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Logger
     */
    protected $logger;

    /** @var  Game|Mock */
    protected $game;

    /**
     * setup config and logger
     */
    public function setUp()
    {
        $this->config = $this->getRealConfig();
        $this->logger = $this->buildTestLogger();
        $this->game   = \Mockery::mock(Game::class, [$this->config, $this->logger]);

        $this->game->shouldReceive('bootstrap')
            ->withNoArgs()
            ->passthru();

        $this->game->shouldReceive('getController')
            ->withNoArgs()
            ->passthru();
    }

    public function tearDown()
    {
        \Mockery::close();

        parent::tearDown();
    }

    /**
     * @return Config
     */
    protected function getRealConfig()
    {
        return Config::load(__DIR__ . '/../../config');
    }

    /**
     * runs the game
     */
    protected function runGame()
    {
        $this->game->bootstrap()->getController()->run();
    }

    /**
     * @return Logger
     */
    private function buildTestLogger()
    {
        return (new Logger('logger'))
            ->pushHandler(new TestHandler)
            ->pushProcessor(new PsrLogMessageProcessor);
    }
}