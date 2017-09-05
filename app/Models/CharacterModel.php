<?php

namespace App\Models;

use App\Events\AttackEvent;
use App\Game;
use App\Listeners\AttackListener;

/**
 * Class CharacterModel
 * @package App\Models
 */
class CharacterModel extends AbstractModel
{
    /**
     * @var
     */
    private $name;


    /**
     * @var CharacterStatsModel
     */
    private $stats;

    /**
     * @var CharacterSkillsCollection
     */
    private $skills = [];

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return CharacterModel
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return CharacterStatsModel
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * @param CharacterStatsModel $stats
     *
     * @return CharacterModel
     */
    public function setStats($stats)
    {
        $this->stats = $stats;

        return $this;
    }

    /**
     * @return CharacterSkillsCollection
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * @param CharacterSkillsCollection $skills
     *
     * @return CharacterModel
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAlive()
    {
        return ($this->getStats()->getHealth() > 0);
    }

    /**
     * @return bool
     */
    public function isLucky()
    {
        return (rand(0, 100) < $this->getStats()->getLuck());
    }

    public function addListeners()
    {
        Game::getEmitter()->addListener(
            AttackEvent::class,
            (new AttackListener)->setDefender($this)
        );
    }
}
