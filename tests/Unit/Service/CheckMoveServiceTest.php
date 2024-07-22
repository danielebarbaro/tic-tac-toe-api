<?php

namespace App\Tests\Unit\Service;

use App\Entity\Game;
use App\Entity\Move;
use App\Enum\GamePlayerEnum;
use App\Enum\GameStatusEnum;
use App\Service\CheckMoveService;
use App\Service\CheckWinnerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CheckMoveServiceTest extends KernelTestCase
{
    private mixed $entityManager;
    private CheckMoveService $checkMoveService;
    private mixed $checkWinnerService;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->checkWinnerService = $container->get(CheckWinnerService::class);
        $this->checkMoveService = new CheckMoveService(
            $this->checkWinnerService,
            $this->entityManager->getRepository(Game::class),
            $this->entityManager->getRepository(Move::class)
        );
    }

    public function testExecuteRecordsMove()
    {
        $game = new Game(2);
        $game->setBoard([0, 0, 0, 0, 0, 0, 0, 0, 0]);
        $this->entityManager->persist($game);
        $this->entityManager->flush();

        $move = new Move($game, 1, GamePlayerEnum::PLAYER_ONE);

        $moveDto = $this->checkMoveService->execute($game, $move);

        $this->entityManager->clear();

        $savedMove = $this->entityManager->getRepository(Move::class)->find($move->getId());
        $this->assertNotNull($savedMove);
        $this->assertEquals(GamePlayerEnum::PLAYER_ONE, $savedMove->getPlayer());
        $this->assertEquals(1, $savedMove->getPosition());
    }

    public function testExecuteUpdatesBoard()
    {
        $game = new Game(2);
        $game->setBoard([0, 0, 0, 0, 0, 0, 0, 0, 0]);
        $this->entityManager->persist($game);
        $this->entityManager->flush();

        $move = new Move($game, 1, GamePlayerEnum::PLAYER_ONE);

        $this->checkMoveService->execute($game, $move);

        $this->entityManager->clear();

        $updatedGame = $this->entityManager->getRepository(Game::class)->find($game->getId());
        $this->assertEquals([1, 0, 0, 0, 0, 0, 0, 0, 0], $updatedGame->getBoard());
    }


    public function testExecuteUpdatesStatusToWon()
    {
        $game = new Game(2);
        $game->setBoard([1, 1, 1, 0, 0, 0, 0, 0, 0]);
        $this->entityManager->persist($game);
        $this->entityManager->flush();

        $move = new Move($game, 3, GamePlayerEnum::PLAYER_ONE);

        $this->checkMoveService->execute($game, $move);

        $this->entityManager->clear();

        $updatedGame = $this->entityManager->getRepository(Game::class)->find($game->getId());
        $this->assertEquals(GameStatusEnum::WON, $updatedGame->getStatus());
    }

    public function testExecuteUpdatesStatusToOngoing()
    {
        $game = new Game(2);
        $game->setBoard([1, 0, 0, 0, 0, 0, 0, 0, 0]);
        $this->entityManager->persist($game);
        $this->entityManager->flush();

        $move = new Move($game, 1, GamePlayerEnum::PLAYER_ONE);

        $this->checkMoveService->execute($game, $move);

        $this->entityManager->clear();

        $updatedGame = $this->entityManager->getRepository(Game::class)->find($game->getId());
        $this->assertEquals(GameStatusEnum::ONGOING, $updatedGame->getStatus());
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
        parent::tearDown();
    }
}
