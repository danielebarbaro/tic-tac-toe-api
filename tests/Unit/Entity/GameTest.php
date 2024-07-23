<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Game;
use App\Enum\GameLevelEnum;
use App\Enum\GameStatusEnum;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GameTest extends KernelTestCase
{
    public function testDefaultValues(): void
    {
        $game = new Game(2);

        $this->assertSame(GameStatusEnum::NEW, $game->getStatus());
        $this->assertSame(GameLevelEnum::NEWBIE, $game->getLevel());
        $this->assertSame(GameStatusEnum::NEW, $game->getStatus());
        $this->assertSame(2, $game->getPlayers());
        $this->assertSame([0, 0, 0, 0, 0, 0, 0, 0, 0], $game->getBoard());
    }

    public function testGameCannotBePlayedWhenStatusIsNotOngoing(): void
    {
        $game = new Game(2);

        $game->setStatus(GameStatusEnum::WON);
        $this->assertFalse($game->canPlay());
    }

    public function testBoardIsFull(): void
    {
        $game = new Game(2);

        $game->setBoard(array_fill(0, Game::BOARD_SIZE, 1));
        $this->assertTrue($game->isBoardFull());
    }

    public function testBoardIsNotFullWhenOnePositionIsTaken(): void
    {
        $game = new Game(2);

        $board = array_fill(0, Game::BOARD_SIZE, 1);
        $board[4] = 0;
        $game->setBoard($board);
        $this->assertFalse($game->isBoardFull());
    }
}
