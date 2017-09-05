<?php

namespace App\Repositories;

use App\Models\CharacterModel;

/**
 * Interface CharacterRepositoryInterface
 * @package App\Repositories
 */
interface CharacterRepositoryInterface
{
    /**
     * @param string $playerName
     *
     * @return CharacterModel
     */
    public function getPlayer($playerName);
}
