<?php

namespace App\Factories;

use App\Models\CharacterModel;
use App\Models\CharacterSkillsCollection;
use App\Models\CharacterStatsModel;
use App\Models\Skills\SkillInterface;

/**
 * Class CharacterFactory
 * @package App\Factories
 */
class CharacterFactory implements CharacterFactoryInterface
{
    private $defaultConfig = [
        'name'   => 'Player',
        'stats'  => [
            'health'   => 0,
            'strength' => 0,
            'defence'  => 0,
            'speed'    => 0,
            'luck'     => 0
        ],
        'skills' => []
    ];

    /**
     * @param string $playerName
     * @param array  $config
     *
     * @return CharacterModel
     */
    public function build($playerName, array $config = [])
    {
        $config = $this->decorateConfig($config);

        $characterModel = new CharacterModel;
        $characterModel->setName($playerName);

        $characterStatsModel = new CharacterStatsModel;
        $characterStatsModel->setHealth($this->rollStat($config['stats']['health']));
        $characterStatsModel->setStrength($this->rollStat($config['stats']['strength']));
        $characterStatsModel->setDefence($this->rollStat($config['stats']['defence']));
        $characterStatsModel->setSpeed($this->rollStat($config['stats']['speed']));
        $characterStatsModel->setLuck($this->rollStat($config['stats']['luck']));
        $characterModel->setStats($characterStatsModel);

        $characterSkillsCollection = new CharacterSkillsCollection;
        foreach ($config['skills'] as $skillClass => $chance) {
            /** @var SkillInterface $skill */
            $skill = new $skillClass($characterModel);
            $skill->setChance($chance);
            $characterSkillsCollection->add($skill);
        }

        $characterModel->setSkills($characterSkillsCollection);

        $characterModel->addListeners();

        return $characterModel;
    }

    /**
     * @param array $config
     *
     * @return array
     */
    private function decorateConfig(array $config = [])
    {
        $decoratedConfig = array_merge($this->defaultConfig, $config);

        if (array_key_exists('stats', $config)) {
            $decoratedConfig['stats'] = array_merge($this->defaultConfig['stats'], $config['stats']);
        }

        return $decoratedConfig;
    }

    /**
     * @param array|int $statRange
     *
     * @return int
     */
    private function rollStat($statRange)
    {
        if (!is_array($statRange) && is_int($statRange)) {
            return $statRange;
        }

        return rand($statRange[0], $statRange[1]);
    }
}
