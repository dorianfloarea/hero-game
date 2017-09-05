<?php

namespace App;

use App\Controllers\GameController;
use App\Factories\CharacterFactory;
use App\Models\CharacterModel;
use App\Repositories\CharacterRepository;
use App\Services\CharacterService;
use App\Services\GameService;
use League\Container\Container;
use League\Event\Emitter;
use Monolog\Logger;
use Noodlehaus\Config;
use Noodlehaus\Exception;

/**
 * Class Game
 * @package App
 */
class Game
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Container
     */
    private static $container;

    /**
     * Game constructor.
     *
     * @param Config $config
     * @param Logger $logger
     *
     * @throws Exception
     */
    public function __construct(Config $config, Logger $logger)
    {
        if (!$config->has('maxTurns')) {
            throw new Exception('The maxTrurns key is mandatory in the config file.');
        }

        if (!$config->has('characters')) {
            throw new Exception('The characters key is mandatory in the config file..');
        }

        if (!$config->has('characters.' . PLAYER_1_NAME) || !$config->has('characters.' . PLAYER_2_NAME)) {
            throw new Exception('The config file is invalid');
        }

        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @return Game
     */
    public function bootstrap()
    {
        self::$container = new Container;
        self::$container->add('config', $this->config);
        self::$container->add('emitter', new Emitter);
        self::$container->add('logger', $this->logger);

        $this->loadDependencies();

        $player1 = $this->getCharacterService()->getPlayer(PLAYER_1_NAME);
        $this->presentPlayer($player1);

        $player2 = $this->getCharacterService()->getPlayer(PLAYER_2_NAME);
        $this->presentPlayer($player2);

        self::$container->add($player1->getName(), $player1);
        self::$container->add($player2->getName(), $player2);

        return $this;
    }

    /**
     * @return Config
     */
    public static function getConfig()
    {
        return self::$container->get('config');
    }

    /**
     * @param string $playerCode
     *
     * @return CharacterModel
     */
    public static function getPlayer($playerCode)
    {
        return self::$container->get($playerCode);
    }

    /**
     * @return Emitter
     */
    public static function getEmitter()
    {
        return self::$container->get('emitter');
    }

    /**
     * @return Logger
     */
    public static function getLogger()
    {
        return self::$container->get('logger');
    }

    /**
     * @return GameController
     */
    public function getController()
    {
        return self::$container->get(GameController::class);
    }

    private function loadDependencies()
    {
        self::$container->add(CharacterFactory::class);

        self::$container->add(GameService::class);

        self::$container->add(CharacterRepository::class)
            ->withArgument(CharacterFactory::class);

        self::$container->add(CharacterService::class)
            ->withArgument(CharacterRepository::class);

        self::$container->add(GameController::class)
            ->withArgument(GameService::class)
            ->withArgument($this->config->get('maxTurns'));
    }

    /**
     * @return CharacterService
     */
    private function getCharacterService()
    {
        return self::$container->get(CharacterService::class);
    }

    /**
     * @param CharacterModel $player
     */
    private function presentPlayer(CharacterModel $player)
    {
        self::getLogger()->info('{playerName} stats:', ['playerName' => $player->getName()]);
        self::getLogger()->info('Health: {health}', ['health' => $player->getStats()->getHealth()]);
        self::getLogger()->info('Strength: {strength}', ['strength' => $player->getStats()->getStrength()]);
        self::getLogger()->info('Defence: {defence}', ['defence' => $player->getStats()->getDefence()]);
        self::getLogger()->info('Speed: {speed}', ['speed' => $player->getStats()->getSpeed()]);
        self::getLogger()->info('Luck: {luck}%', ['luck' => $player->getStats()->getLuck()]);
        self::getLogger()->info('---');
    }
}
