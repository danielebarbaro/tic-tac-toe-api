<?php

namespace App\Tests\Entity;

use App\Entity\Game;
use App\Entity\Move;
use App\Enum\GameLevelEnum;
use App\Enum\GamePlayerEnum;
use App\Enum\GameStatusEnum;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MoveTest extends KernelTestCase
{
    public function testDefaultValues(): void
    {
        $move = new Move(new Game(2), 1, GamePlayerEnum::PLAYER_ONE);

        $this->assertSame(GamePlayerEnum::PLAYER_ONE,$move->getPlayer());
        $this->assertSame( 1,$move->getPosition());
        $this->assertInstanceOf(Game::class, $move->getGame());
    }
}