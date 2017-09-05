<?php namespace App\Controllers;

use App\Events\AttackEvent;
use App\Game;
use App\Models\CharacterModel;
use App\Services\GameServiceInterface;

/**
 * Class GameController
 * @package App\Controllers
 */
class GameController
{
    /**
     * @var GameServiceInterface
     */
    private $gameService;

    /**
     * @var int
     */
    private $turnsLeft;
    /**
     * @var CharacterModel
     */
    private $attackingPlayer;

    /**
     * GameController constructor.
     *
     * @param GameServiceInterface $gameService
     * @param int                  $maxTurns
     */
    public function __construct(GameServiceInterface $gameService, $maxTurns = 20)
    {
        $this->gameService = $gameService;
        $this->turnsLeft   = $maxTurns;
    }

    /**
     * Starts the game
     */
    public function run()
    {
        $this->attackingPlayer = $this->gameService->getFirstAttacker();

        while ($this->turnsLeft > 0 && $this->gameService->playersAreAlive()) {
            Game::getLogger()->info('{name} is now attacking', [
                'name' => $this->attackingPlayer->getName()
            ]);

            Game::getEmitter()->emit(
                (new AttackEvent)->setAttacker($this->attackingPlayer)
            );

            $this->attackingPlayer = $this->gameService->getNextAttacker($this->attackingPlayer);
            $this->turnsLeft--;
        }

        Game::getLogger()->info('---');

        if ($this->gameService->playersAreAlive()) {
            Game::getLogger()->info('Both players are exhausted and retreat slowly. It\'s a draw.');
        } else {
            $player1 = Game::getPlayer(PLAYER_1_NAME);
            $player2 = Game::getPlayer(PLAYER_2_NAME);
            $winner  = ($player1->isAlive()) ? $player1 : $player2;
            Game::getLogger()->info('{player} is victorious!', ['player' => $winner->getName()]);
        }
    }
}
