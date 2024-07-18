<?php

namespace App\Service;

use App\Dto\MoveDto;
use App\Entity\Game;
use App\Entity\Move;
use App\Enum\GamePlayerEnum;
use App\Enum\GameStatusEnum;
use App\Repository\GameRepository;
use App\Repository\MoveRepository;

class CheckMoveService
{
    private CheckWinnerService $checkWinnerService;
    private GameRepository $gameRepository;
    private MoveRepository $moveRepository;

    public function __construct(
        CheckWinnerService $checkWinnerService,
        GameRepository $gameRepository,
        MoveRepository $moveRepository
    ) {
        $this->checkWinnerService = $checkWinnerService;
        $this->gameRepository = $gameRepository;
        $this->moveRepository = $moveRepository;
    }

    public function execute(Game $game, Move $move): MoveDto
    {
        $game = $this->gameRepository->find($game->getId());

        $board = $game->getBoard();
        $player = $move->getPlayer();

        $board[$move->getPosition() - 1] = $player === GamePlayerEnum::PLAYER_ONE ? 1 : -1;

        $winner = $this->checkWinnerService->execute($board);
        $game->setBoard($board);
        if (count($game->getMoves()) === 0) {
            $game->setStatus(GameStatusEnum::ONGOING);
        }

        if ($winner !== null) {
            $game->setStatus(GameStatusEnum::WON);
            $game->setWinner($winner);
            $game->setGameCompletedAt(new \DateTimeImmutable('now'));
        }

        $this->gameRepository->save($game);
        $this->moveRepository->save($move);

        $moveDto = new MoveDto();
        $moveDto->id = $move->getId();
        $moveDto->player = $player;
        $moveDto->position = $move->getPosition();
        $moveDto->nextPlayer = $player === GamePlayerEnum::PLAYER_ONE ?
            GamePlayerEnum::PLAYER_TWO :
            GamePlayerEnum::PLAYER_ONE;
        $moveDto->isGameOver = $winner !== null;
        $moveDto->winner = $winner;

        return $moveDto;
    }
}