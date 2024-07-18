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

        $this->updateBoard($game, $move);
        $this->updateGameStatus($game);

        $this->gameRepository->save($game);
        $this->moveRepository->save($move);

        return $this->createMoveDto($move, $game);
    }

    private function updateBoard(Game $game, Move $move): void
    {
        $board = $game->getBoard();
        $player = $move->getPlayer();
        $position = $move->getPosition() - 1;

        $board[$position] = $player === GamePlayerEnum::PLAYER_ONE ? 1 : -1;
        $game->setBoard($board);
    }

    private function updateGameStatus(Game $game): void
    {
        $winner = $this->checkWinnerService->execute($game->getBoard());

        if (count($game->getMoves()) === 0) {
            $game->setStatus(GameStatusEnum::ONGOING);
        }

        if (count($game->getMoves()) === Game::BOARD_SIZE - 1 && $winner === null) {
            $game->setStatus(GameStatusEnum::TIE);
            $game->setGameCompletedAt(new \DateTimeImmutable('now'));
        }

        if ($winner !== null) {
            $game->setStatus(GameStatusEnum::WON);
            $game->setWinner($winner);
            $game->setGameCompletedAt(new \DateTimeImmutable('now'));
        }
    }

    private function createMoveDto(Move $move, Game $game): MoveDto
    {
        $moveDto = new MoveDto();
        $moveDto->id = $move->getId();
        $moveDto->player = $move->getPlayer();
        $moveDto->position = $move->getPosition();
        $moveDto->nextPlayer = $move->getPlayer() === GamePlayerEnum::PLAYER_ONE ?
            GamePlayerEnum::PLAYER_TWO :
            GamePlayerEnum::PLAYER_ONE;
        $moveDto->isGameOver = $game->getStatus() !== GameStatusEnum::ONGOING;
        $moveDto->winner = $game->getWinner();

        return $moveDto;
    }
}