<?php

namespace Tests\App\Models\Skills;

use App\Models\Skills\RapidStrikeSkill;
use Monolog\Handler\TestHandler;
use Tests\App\TestCase;

/**
 * Class RapidStrikeSkillTest
 * @package Tests\App\Models\Skills
 */
class RapidStrikeSkillTest extends TestCase
{
    /**
     * run game with mocked config where Orderus triggers the skill
     */
    public function testRunGameWithMockedConfigWhereOrderusTriggersTheSkill()
    {
        $this->config->set('characters.' . PLAYER_1_NAME . '.skills', [
            RapidStrikeSkill::class => 100
        ]);
        $this->config->set('characters.' . PLAYER_1_NAME . '.stats.luck', 100);

        $this->config->set('characters.' . PLAYER_2_NAME . '.skills', []);

        $this->runGame();

        /** @var TestHandler $logHandler */
        $logHandler = $this->logger->popHandler();
        self::assertTrue(
            $logHandler->hasInfoThatContains(PLAYER_1_NAME . ' triggers Rapid Strike and gains one extra attack.')
        );
    }
}
