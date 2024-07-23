<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Game;
use App\Enum\GameStatusEnum;
use App\Exception\GameFinishedException;
use App\Service\GameStateMachineService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GameStateMachineServiceTest extends WebTestCase
{
    private $gameStateMachineService;

    protected function setUp(): void
    {
        $this->gameStateMachineService = new GameStateMachineService();
    }

    public function testExecuteGameStatusTie()
    {
        $this->expectException(GameFinishedException::class);
        $this->expectExceptionMessage('Game is already over');

        $game = new Game(2);
        $game->setStatus(GameStatusEnum::TIE);

        $this->gameStateMachineService->execute($game);
    }

    public function testExecuteGameStatusWon()
    {
        $this->expectException(GameFinishedException::class);
        $this->expectExceptionMessage('Game winner is already set');

        $game = new Game(2);
        $game->setStatus(GameStatusEnum::WON);

        $this->gameStateMachineService->execute($game);
    }

    public function testExecuteGameStatusOther()
    {
        $game = new Game(2);
        $game->setStatus(GameStatusEnum::ONGOING);

        // No exception should be thrown
        $this->gameStateMachineService->execute($game);

        $this->addToAssertionCount(1); // Ensure the test counts as passed
    }

    public function testExecuteGameStatusCodeTie()
    {
        $game = new Game(2);
        $game->setStatus(GameStatusEnum::TIE);

        try {
            $this->gameStateMachineService->execute($game);
        } catch (GameFinishedException $e) {
            $this->assertSame('Game is already over', $e->getMessage());
            $this->assertSame(Response::HTTP_BAD_REQUEST, $e->getStatusCode());
        }
    }

    public function testExecuteGameStatusCodeWon()
    {
        $game = new Game(2);
        $game->setStatus(GameStatusEnum::WON);

        try {
            $this->gameStateMachineService->execute($game);
        } catch (GameFinishedException $e) {
            $this->assertSame('Game winner is already set', $e->getMessage());
            $this->assertSame(Response::HTTP_BAD_REQUEST, $e->getStatusCode());
        }
    }
}
