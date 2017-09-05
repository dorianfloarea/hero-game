<?php

namespace App\Services;

use App\Game;
use App\Models\CharacterModel;

/**
 * Class GameService
 * @package App\Services
 */
class GameService implements GameServiceInterface
{
    /**
     * @return bool
     */
    public function playersAreAlive()
    {
        return (Game::getPlayer(PLAYER_1_NAME)->isAlive() && Game::getPlayer(PLAYER_2_NAME)->isAlive());
    }

    /**
     * @return CharacterModel
     */
    public function getFirstAttacker()
    {
        $player1  = Game::getPlayer(PLAYER_1_NAME);
        $player2  = Game::getPlayer(PLAYER_2_NAME);
        $attacker = null;

        if ($player1->getStats()->getSpeed() > $player2->getStats()->getSpeed()) {
            $attacker = $player1;
        } elseif ($player1->getStats()->getSpeed() < $player2->getStats()->getSpeed()) {
            $attacker = $player2;
        }

        if ($attacker !== null) {
            Game::getLogger()->info('{name} is the fastest and will strike first.', [
                'name' => $attacker->getName()
            ]);
        } else {
            $attacker = ($player1->getStats()->getLuck() > $player2->getStats()->getLuck())
                ? $player1
                : $player2;
            Game::getLogger()->info('Though being equally fast, {name} is luckier and will strike first.', [
                'name' => $attacker->getName()
            ]);
        }

        return $attacker;
    }

    /**
     * @param CharacterModel $attackingPlayer
     *
     * @return CharacterModel
     */
    public function getNextAttacker(CharacterModel $attackingPlayer)
    {
        $player1 = Game::getPlayer(PLAYER_1_NAME);
        $player2 = Game::getPlayer(PLAYER_2_NAME);

        return ($attackingPlayer->getName() === $player1->getName())
            ? $player2
            : $player1;
    }
}
