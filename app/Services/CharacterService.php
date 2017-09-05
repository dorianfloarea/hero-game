<?php

namespace App\Services;

use App\Models\CharacterModel;
use App\Repositories\CharacterRepositoryInterface;

/**
 * Class CharacterService
 * @package App\Services
 */
class CharacterService implements CharacterServiceInterface
{
    /**
     * @var CharacterRepositoryInterface
     */
    private $characterRepository;

    public function __construct(CharacterRepositoryInterface $characterRepository)
    {
        $this->characterRepository = $characterRepository;
    }

    /**
     * @param string $playerName
     *
     * @return CharacterModel
     */
    public function getPlayer($playerName)
    {
        return $this->characterRepository->getPlayer($playerName);
    }
}
