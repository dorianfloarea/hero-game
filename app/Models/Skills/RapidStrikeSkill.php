<?php

namespace App\Models\Skills;

use App\Events\AttackEvent;
use App\Game;

/**
 * Class RapidStrikeSkill
 * @package App\Models\Skills
 */
class RapidStrikeSkill extends AbstractOffensiveSkill
{
    /**
     * @var string
     */
    protected $name = 'Rapid Strike';

    public function handle()
    {
        Game::getLogger()->info('{attacker} triggers {skill} and gains one extra attack.', [
            'attacker' => $this->owner->getName(),
            'skill'    => $this->getName()
        ]);

        Game::getEmitter()->emit(
            (new AttackEvent)->setDirectAttack(false)->setAttacker($this->owner)
        );
    }
}
