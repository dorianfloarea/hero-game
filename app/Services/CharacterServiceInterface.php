<?php

namespace App\Services;

use App\Models\CharacterModel;

/**
 * Interface CharacterServiceInterface
 * @package App\Services
 */
interface CharacterServiceInterface
{
    /**
     * @param string $playerName
     *
     * @return CharacterModel
     */
    public function getPlayer($playerName);
}
