<?php

namespace App\Factories;

use App\Models\CharacterModel;

/**
 * Interface CharacterFactoryInterface
 * @package App\Factories
 */
interface CharacterFactoryInterface
{
    /**
     * @param string $playerName
     * @param array  $config
     *
     * @return CharacterModel
     */
    public function build($playerName, array $config = []);
}
