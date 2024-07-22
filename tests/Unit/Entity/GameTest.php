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
}
