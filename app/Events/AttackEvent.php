<?php

namespace App\Events;

use App\Models\CharacterModel;
use App\Models\CharacterSkillsCollection;
use App\Models\Skills\AbstractOffensiveSkill;
use App\Models\Skills\SkillInterface;
use League\Event\AbstractEvent;

/**
 * Class AttackEvent
 * @package App\Events
 */
class AttackEvent extends AbstractEvent
{
    /**
     * @var CharacterModel
     */
    private $attacker;

    /**
     * @var CharacterSkillsCollection
     */
    private $activeSkills;

    /**
     * @var bool
     */
    private $directAttack = true;

    /**
     * @return CharacterModel
     */
    public function getAttacker()
    {
        return $this->attacker;
    }

    /**
     * @param CharacterModel $attacker
     *
     * @return AttackEvent
     */
    public function setAttacker($attacker)
    {
        $this->attacker = $attacker;

        $this->setActiveSkills();

        return $this;
    }

    /**
     * @return bool
     */
    public function isDirectAttack()
    {
        return $this->directAttack;
    }

    /**
     * @param bool $directAttack
     *
     * @return AttackEvent
     */
    public function setDirectAttack($directAttack)
    {
        $this->directAttack = $directAttack;

        return $this;
    }

    /**
     * @return CharacterSkillsCollection
     */
    public function getActiveSkills()
    {
        return $this->activeSkills;
    }

    /**
     * @return AttackEvent
     */
    private function setActiveSkills()
    {
        $this->activeSkills = new CharacterSkillsCollection;

        if ($this->isDirectAttack()) {
            /** @var SkillInterface $skill */
            foreach ($this->attacker->getSkills() as $skill) {
                if (($skill instanceof AbstractOffensiveSkill) && $skill->shouldTrigger()) {
                    $this->activeSkills->add($skill);
                }
            }
        }

        return $this;
    }
}
