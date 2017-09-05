<?php

namespace App\Models\Skills;

use App\Models\CharacterModel;

/**
 * Class AbstractSkill
 * @package App\Models\Skills
 */
abstract class AbstractSkill implements SkillInterface
{
    /**
     * @var CharacterModel
     */
    protected $owner;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    private $chance;

    /**
     * AbstractSkill constructor.
     *
     * @param CharacterModel $owner
     */
    public function __construct(CharacterModel $owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return AbstractSkill
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getChance()
    {
        return $this->chance;
    }

    /**
     * @param int $chance
     *
     * @return AbstractSkill
     */
    public function setChance($chance)
    {
        $this->chance = $chance;

        return $this;
    }

    /**
     * @return bool
     */
    public function shouldTrigger()
    {
        return (rand(0, 100) < $this->chance);
    }
}
