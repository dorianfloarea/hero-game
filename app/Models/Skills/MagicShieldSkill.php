<?php

namespace App\Models\Skills;

use App\Game;

/**
 * Class MagicShieldSkill
 * @package App\Models\Skills
 */
class MagicShieldSkill extends AbstractDefensiveSkill
{
    /**
     * @var string
     */
    protected $name = 'Magic Shield';

    /**
     * @param int $rawDamage
     *
     * @return int
     */
    public function handle($rawDamage)
    {
        Game::getLogger()->info('{defender} triggers {skill} and the next attack will be reduced to half.', [
            'defender' => $this->owner->getName(),
            'skill'    => $this->getName()
        ]);

        return ($rawDamage / 2);
    }
}
