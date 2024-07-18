<?php

namespace App\Service;

use App\Entity\Game;
use App\Enum\GameStatusEnum;
use App\Exception\GameFinishedException;
use HttpException;
use Symfony\Component\HttpFoundation\Response;

class GameStateMachineService
{
    /**
     * @throws HttpException
     */
    public function execute(Game $game): void
    {
        if ($game->getStatus() === GameStatusEnum::TIE) {
            throw new GameFinishedException('Game is already over', Response::HTTP_BAD_REQUEST);
        } elseif ($game->getStatus() == GameStatusEnum::WON) {
            throw new GameFinishedException('Game winner is already set', Response::HTTP_BAD_REQUEST);
        }
    }
}