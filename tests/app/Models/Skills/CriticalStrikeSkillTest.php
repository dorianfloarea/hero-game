<?php

namespace Tests\App\Models\Skills;

use App\Models\Skills\CriticalStrikeSkill;
use Monolog\Handler\TestHandler;
use Tests\App\TestCase;

/**
 * Class CriticalStrikeSkillTest
 * @package Tests\App\Models\Skills
 */
class CriticalStrikeSkillTest extends TestCase
{
    /**
     * run game with mocked config where Wild Beast triggers the skill
     */
    public function testRunGameWithMockedConfigWhereWildBeastTriggersTheSkill()
    {
        $this->config->set('characters.' . PLAYER_2_NAME . '.skills', [
            CriticalStrikeSkill::class => 100
        ]);

        $this->config->set('characters.' . PLAYER_1_NAME . '.skills', []);
        $this->config->set('characters.' . PLAYER_1_NAME . '.stats.luck', 0);

        $this->runGame();

        /** @var TestHandler $logHandler */
        $logHandler = $this->logger->popHandler();
        self::assertTrue(
            $logHandler->hasInfoThatContains(
                PLAYER_2_NAME . ' triggers Critical Strike and gains a multiplier of 2 for the next attack.'
            )
        );
    }
}
