<?php

namespace Tests\App\Models\Skills;

use App\Models\Skills\ThickSkinSkill;
use Monolog\Handler\TestHandler;
use Tests\App\TestCase;

/**
 * Class ThickSkinSkillTest
 * @package Tests\App\Models\Skills
 */
class ThickSkinSkillTest extends TestCase
{
    /**
     * run game with mocked config where Orderus triggers the skill
     */
    public function testRunGameWithMockedConfigWhereOrderusTriggersTheSkill()
    {
        $this->config->set('characters.' . PLAYER_1_NAME . '.skills', [
            ThickSkinSkill::class => 100
        ]);
        $this->config->set('characters.' . PLAYER_1_NAME . '.stats.luck', 0);

        $this->config->set('characters.' . PLAYER_2_NAME . '.skills', []);

        $this->runGame();

        /** @var TestHandler $logHandler */
        $logHandler = $this->logger->popHandler();
        self::assertTrue(
            $logHandler->hasInfoThatContains(
                PLAYER_1_NAME . ' triggers Thick Skin and boosts it\'s defence by 30.'
            )
        );
    }
}
