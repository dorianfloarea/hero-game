<?php

namespace App\Models\Skills;

use App\Game;

/**
 * Class CriticalStrikeSkill
 * @package App\Models\Skills
 */
class CriticalStrikeSkill extends AbstractOffensiveSkill
{
    /**
     * @var string
     */
    protected $name = 'Critical Strike';

    /**
     * @var int
     */
    private $enhancement = 2;

    public function handle()
    {
        Game::getLogger()->info(
            '{attacker} triggers {skill} and gains a multiplier of {enhancement} for the next attack.',
            [
                'attacker'    => $this->owner->getName(),
                'skill'       => $this->getName(),
                'enhancement' => $this->enhancement
            ]
        );

        // stats will reset after each attack
        $strength = $this->owner->getStats()->getStrength();
        $this->owner->getStats()->setStrength($strength * $this->enhancement);
    }
}
