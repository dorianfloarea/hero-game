<?php

namespace App\Listeners;

use App\Events\AttackEvent;
use App\Game;
use App\Models\CharacterModel;
use App\Models\CharacterSkillsCollection;
use App\Models\Skills\AbstractDefensiveSkill;
use App\Models\Skills\SkillInterface;
use League\Event\AbstractListener;
use League\Event\EventInterface;
use Noodlehaus\Exception;

/**
 * Class AttackListener
 * @package App\Listeners
 */
class AttackListener extends AbstractListener
{
    /**
     * @var CharacterModel
     */
    private $defender;

    /**
     * @var CharacterSkillsCollection
     */
    private $activeSkills;

    /**
     * @return CharacterModel
     */
    public function getDefender()
    {
        return $this->defender;
    }

    /**
     * @param CharacterModel $defender
     *
     * @return AttackListener
     */
    public function setDefender($defender)
    {
        $this->defender = $defender;

        return $this;
    }

    /**
     * @param EventInterface $event
     *
     * @throws Exception
     */
    public function handle(EventInterface $event)
    {
        $this->setDefenderActiveSkills();

        if (!($event instanceof AttackEvent)) {
            throw new Exception('Invalid event.');
        }

        if ($event->getAttacker() === $this->defender) {
            return;
        }

        // clone the stats in case any activated skill modifies them
        $attackerInitialStats = clone $event->getAttacker()->getStats();

        $this->handleAttackerSkills($event);

        $rawDamage = $event->getAttacker()->getStats()->getStrength();

        if ($this->defender->isLucky()) {
            Game::getLogger()->info('{attacker} strikes but {defender} got lucky and the attack missed.', [
                'attacker' => $event->getAttacker()->getName(),
                'defender' => $this->defender->getName()
            ]);
        } else {
            Game::getLogger()->info('{attacker} strikes at {defender} and hits for {damage}', [
                'attacker' => $event->getAttacker()->getName(),
                'defender' => $this->defender->getName(),
                'damage'   => $rawDamage

            ]);

            $this->applyDamage($rawDamage);
        }

        // reset the stats to their original value
        $event->getAttacker()->setStats($attackerInitialStats);
    }

    /**
     * @return AttackListener
     */
    private function setDefenderActiveSkills()
    {
        $this->activeSkills = new CharacterSkillsCollection;

        /** @var SkillInterface $skill */
        foreach ($this->defender->getSkills() as $skill) {
            if (($skill instanceof AbstractDefensiveSkill) && $skill->shouldTrigger()) {
                $this->activeSkills->add($skill);
            }
        }

        return $this;
    }

    /**
     * @param AttackEvent $event
     */
    private function handleAttackerSkills(AttackEvent $event)
    {
        /** @var SkillInterface $skill */
        foreach ($event->getActiveSkills() as $skill) {
            $skill->handle();
        }
    }

    /**
     * @param int $rawDamage
     *
     * @return int
     */
    private function handleDefenderSkills($rawDamage)
    {
        /** @var SkillInterface $skill */
        foreach ($this->activeSkills as $skill) {
            $rawDamage = $skill->handle($rawDamage);
        }

        return $rawDamage;
    }

    /**
     * @param int $rawDamage
     */
    private function applyDamage($rawDamage)
    {
        if (!$this->defender->isAlive()) {
            return;
        }

        // clone the stats in case any activated skill modifies them
        $defenderInitialStats = clone $this->defender->getStats();

        // the damage taken could be mitigated by skills
        $rawDamage = $this->handleDefenderSkills($rawDamage);

        $health          = $this->defender->getStats()->getHealth();
        $defence         = $this->defender->getStats()->getDefence();
        $damage          = ($rawDamage >= $defence) ? ($rawDamage - $defence) : 0;
        $absorbedDamage  = $rawDamage - $damage;
        $remainingHealth = $health - $damage;
        $damageTaken     = ($health > $damage) ? $damage : $health;
        $overkillDamage  = ($health > $damage) ? 0 : ($damage - $health);


        if ($health > $damage) {
            $this->defender->getStats()->setHealth($remainingHealth);
            Game::getLogger()->info(
                '{defender} received a hit of {damage} ({absorbedDamage} absorbed) and his health is now down to ' .
                '{health}',
                [
                    'defender'       => $this->defender->getName(),
                    'damage'         => $damageTaken,
                    'absorbedDamage' => $absorbedDamage,
                    'health'         => $remainingHealth
                ]
            );
        } else {
            Game::getLogger()->info(
                '{defender} receives a hit of {damage} ({absorbedDamage} absorbed, {overkill} overkill) and ' .
                'falls to the ground.',
                [
                    'defender'       => $this->defender->getName(),
                    'damage'         => $damageTaken,
                    'absorbedDamage' => $absorbedDamage,
                    'overkill'       => $overkillDamage
                ]
            );
            $this->defender->getStats()->setHealth(0);
        }

        // reset the stats to their original value, without health of course
        $defenderInitialStats->setHealth($this->defender->getStats()->getHealth());
        $this->defender->setStats($defenderInitialStats);
    }
}
