<?php

namespace App\Tests\Unit\Service;

use App\Enum\GamePlayerEnum;
use App\Service\CheckWinnerService;
use App\Tests\MatchHelper;
use PHPUnit\Framework\TestCase;

class CheckWinnerServiceTest extends TestCase
{
    private array $matchHelper;
    private CheckWinnerService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new CheckWinnerService();
        $this->matchHelper = MatchHelper::getWins();
    }

    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    public function testNoWinnerWithEmptyBoard()
    {
        $case = $this->matchHelper['No Winner With Empty Board'];
        $this->assertNull($this->service->execute($case['board']));
    }

    public function testPlayerOneWinsWithVerticalLine()
    {
        $case = $this->matchHelper['Player One Wins With Vertical Line'];
        $this->assertEquals(GamePlayerEnum::PLAYER_ONE, $this->service->execute($case['board']));
    }

    public function testPlayerOneWinsWithDiagonalLine()
    {
        $case = $this->matchHelper['Player One Wins With Diagonal Line'];
        $this->assertEquals(GamePlayerEnum::PLAYER_ONE, $this->service->execute($case['board']));
    }

    public function testPlayerTwoWinsWithVerticalLine()
    {
        $case = $this->matchHelper['Player Two Wins With Vertical Line'];
        $this->assertEquals(GamePlayerEnum::PLAYER_TWO, $this->service->execute($case['board']));
    }

    public function testPlayerOneWinsWithHorizontalLine()
    {
        $case = $this->matchHelper['Player One Wins With Horizontal Line'];
        $this->assertEquals(GamePlayerEnum::PLAYER_ONE, $this->service->execute($case['board']));
    }

    public function testPlayerTwoWinsWithHorizontalLine()
    {
        $case = $this->matchHelper['Player Two Wins With Horizontal Line'];
        $this->assertEquals(GamePlayerEnum::PLAYER_TWO, $this->service->execute($case['board']));
    }
}
