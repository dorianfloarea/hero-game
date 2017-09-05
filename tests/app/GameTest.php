<?php

namespace Tests\App;

use App\Game;
use App\Models\CharacterSkillsCollection;
use App\Models\CharacterStatsModel;
use App\Models\Skills\SkillInterface;

/**
 * Class GameTest
 * @package Tests\App
 */
class GameTest extends TestCase
{
    /**
     * characters are created correctly
     */
    public function testCharactersAreCreatedCorrectly()
    {
        $this->game->bootstrap();

        $player1 = Game::getPlayer(PLAYER_1_NAME);

        self::assertEquals(PLAYER_1_NAME, $player1->getName());
        $this->checkStats($this->config->get('characters.' . PLAYER_1_NAME . '.stats'), $player1->getStats());
        $this->checkSkills($this->config->get('characters.' . PLAYER_1_NAME . '.skills'), $player1->getSkills());

        $player2 = Game::getPlayer(PLAYER_2_NAME);

        self::assertEquals(PLAYER_2_NAME, $player2->getName());
        $this->checkStats($this->config->get('characters.' . PLAYER_2_NAME . '.stats'), $player2->getStats());
        $this->checkSkills($this->config->get('characters.' . PLAYER_2_NAME . '.skills'), $player2->getSkills());
    }

    /**
     * @param array               $configStats
     * @param CharacterStatsModel $playerStats
     */
    private function checkStats(array $configStats, CharacterStatsModel $playerStats)
    {
        $this->checkStat($configStats['health'], $playerStats->getHealth());
        $this->checkStat($configStats['strength'], $playerStats->getStrength());
        $this->checkStat($configStats['defence'], $playerStats->getDefence());
        $this->checkStat($configStats['speed'], $playerStats->getSpeed());
        $this->checkStat($configStats['luck'], $playerStats->getLuck());
    }

    /**
     * @param array $statsRange
     * @param int   $playerStat
     */
    private function checkStat(array $statsRange, $playerStat)
    {
        self::assertGreaterThanOrEqual($statsRange[0], $playerStat);
        self::assertLessThanOrEqual($statsRange[1], $playerStat);
    }

    /**
     * @param array                     $configSkills
     * @param CharacterSkillsCollection $playerSkills
     */
    private function checkSkills(array $configSkills, CharacterSkillsCollection $playerSkills)
    {
        self::assertCount(count($configSkills), $playerSkills);

        /** @var SkillInterface $skill */
        foreach ($playerSkills as $skill) {
            $skillClass = get_class($skill);
            self::assertArrayHasKey($skillClass, $configSkills);
            $configSkillChance = $configSkills[$skillClass];
            self::assertEquals($configSkillChance, $skill->getChance());
        }
    }
}
