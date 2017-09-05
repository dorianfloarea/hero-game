<?php

namespace Tests\App\Models\Skills;

use App\Models\Skills\MagicShieldSkill;
use Monolog\Handler\TestHandler;
use Tests\App\TestCase;

/**
 * Class MagicShieldSkillTest
 * @package Tests\App\Models\Skills
 */
class MagicShieldSkillTest extends TestCase
{
    /**
     * run game with mocked config where Orderus triggers the skill
     */
    public function testRunGameWithMockedConfigWhereOrderusTriggersTheSkill()
    {
        $this->config->set('characters.' . PLAYER_1_NAME . '.skills', [
            MagicShieldSkill::class => 100
        ]);
        $this->config->set('characters.' . PLAYER_1_NAME . '.stats.luck', 0);

        $this->config->set('characters.' . PLAYER_2_NAME . '.skills', []);

        $this->runGame();

        /** @var TestHandler $logHandler */
        $logHandler = $this->logger->popHandler();
        self::assertTrue(
            $logHandler->hasInfoThatContains(
                PLAYER_1_NAME . ' triggers Magic Shield and the next attack will be reduced to half.'
            )
        );
    }
}
