<?php

namespace App\Repositories;

use App\Factories\CharacterFactoryInterface;
use App\Game;
use App\Models\CharacterModel;

/**
 * Class CharacterRepository
 * @package App\Repositories
 */
class CharacterRepository implements CharacterRepositoryInterface
{
    /**
     * @var CharacterFactoryInterface
     */
    private $characterFactory;

    public function __construct(CharacterFactoryInterface $characterFactory)
    {
        $this->characterFactory = $characterFactory;
    }

    /**
     * @param string $playerName
     *
     * @return CharacterModel
     */
    public function getPlayer($playerName)
    {
        $playerConfig = Game::getConfig()->get('characters.' . $playerName);

        return $this->characterFactory->build($playerName, $playerConfig);
    }
}
