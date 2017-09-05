<?php

namespace App\Models\Skills;

use App\Game;

/**
 * Class ThickSkinSkill
 * @package App\Models\Skills
 */
class ThickSkinSkill extends AbstractDefensiveSkill
{
    /**
     * @var string
     */
    protected $name = 'Thick Skin';

    /**
     * @var int
     */
    private $enhancement = 30;

    /**
     * @param int $rawDamage
     *
     * @return int
     */
    public function handle($rawDamage)
    {
        Game::getLogger()->info('{defender} triggers {skill} and boosts it\'s defence by {enhancement}.', [
            'defender'    => $this->owner->getName(),
            'skill'       => $this->getName(),
            'enhancement' => $this->enhancement
        ]);

        // stats will reset after each attack
        $defence = $this->owner->getStats()->getDefence();
        $this->owner->getStats()->setDefence($defence + $this->enhancement);

        return $rawDamage;
    }
}
