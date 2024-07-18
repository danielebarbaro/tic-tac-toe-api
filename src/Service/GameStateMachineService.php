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
        switch ($game->getStatus()) {
            case GameStatusEnum::TIE:
                throw new HttpException('Game is already over', Response::HTTP_BAD_REQUEST);
            case GameStatusEnum::WON:
                throw new GameFinishedException('Game winner is already set', Response::HTTP_BAD_REQUEST);
        }
    }
}