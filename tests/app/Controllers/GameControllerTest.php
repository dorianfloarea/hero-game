<?php

namespace Tests\App\Controllers;

use Monolog\Handler\TestHandler;
use Tests\App\TestCase;

/**
 * Class GameControllerTest
 * @package Tests\App\Controllers
 */
class GameControllerTest extends TestCase
{
    /**
     * run game with mocked config without skills where Orderus starts first and wins
     */
    public function testRunGameWithMockedConfigWithoutSkillsWhereOrderusStartsFirstAndWins()
    {
        $this->config->set('characters.' . PLAYER_1_NAME . '.skills', []);
        $this->config->set('characters.' . PLAYER_1_NAME . '.stats.strength', 100);
        $this->config->set('characters.' . PLAYER_1_NAME . '.stats.speed', 100);
        $this->config->set('characters.' . PLAYER_1_NAME . '.stats.luck', 100);

        $this->config->set('characters.' . PLAYER_2_NAME . '.skills', []);
        $this->config->set('characters.' . PLAYER_2_NAME . '.stats.speed', 50);
        $this->config->set('characters.' . PLAYER_2_NAME . '.stats.luck', 0);
        $this->config->set('characters.' . PLAYER_2_NAME . '.stats.defence', 0);

        $this->runGame();

        /** @var TestHandler $logHandler */
        $logHandler = $this->logger->popHandler();
        self::assertTrue($logHandler->hasInfoThatContains(PLAYER_1_NAME . ' is the fastest and will strike first.'));
        self::assertTrue($logHandler->hasInfoThatContains(PLAYER_1_NAME . ' is victorious!'));
    }

    /**
     * run game with mocked config without skills where WildBeast starts first and wins
     */
    public function testRunGameWithMockedConfigWithoutSkillsWhereWildBeastStartsFirstAndWins()
    {
        $this->config->set('characters.' . PLAYER_2_NAME . '.skills', []);
        $this->config->set('characters.' . PLAYER_2_NAME . '.stats.strength', 100);
        $this->config->set('characters.' . PLAYER_2_NAME . '.stats.speed', 100);
        $this->config->set('characters.' . PLAYER_2_NAME . '.stats.luck', 100);

        $this->config->set('characters.' . PLAYER_1_NAME . '.skills', []);
        $this->config->set('characters.' . PLAYER_1_NAME . '.stats.speed', 50);
        $this->config->set('characters.' . PLAYER_1_NAME . '.stats.luck', 0);
        $this->config->set('characters.' . PLAYER_1_NAME . '.stats.defence', 0);

        $this->runGame();

        /** @var TestHandler $logHandler */
        $logHandler = $this->logger->popHandler();
        self::assertTrue($logHandler->hasInfoThatContains(PLAYER_2_NAME . ' is the fastest and will strike first.'));
        self::assertTrue($logHandler->hasInfoThatContains(PLAYER_2_NAME . ' is victorious!'));
    }
}
