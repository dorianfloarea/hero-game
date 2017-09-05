<?php

namespace App\Services;

use App\Models\CharacterModel;

/**
 * Interface GameServiceInterface
 * @package App\Services
 */
interface GameServiceInterface
{
    /**
     * @return bool
     */
    public function playersAreAlive();

    /**
     * @return CharacterModel
     */
    public function getFirstAttacker();

    /**
     * @param CharacterModel $attackingPlayer
     *
     * @return CharacterModel
     */
    public function getNextAttacker(CharacterModel $attackingPlayer);
}
