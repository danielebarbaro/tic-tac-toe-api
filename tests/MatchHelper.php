<?php

namespace App\Tests;

use App\Enum\GamePlayerEnum;

class MatchHelper
{
    public static function getWins(): array
    {
        return [
            "No Winner With Empty Board" => [
                'board' => [0, 0, 0, 0, 0, 0, 0, 0, 0],
                'winner' => null,
            ],

            "Player One Wins With Vertical Line" => [
                'board' => [1, -1, -1, 1, 1, 0, 1, 0, -1],
                'winner' => GamePlayerEnum::PLAYER_ONE,
            ],

            "Player One Wins With Diagonal Line" => [
                'board' => [1, 1, 1, 0, 0, 0, -1, -1, -1],
                'winner' => GamePlayerEnum::PLAYER_ONE,
            ],

            "Player Two Wins With Vertical Line" => [
                'board' => [1, -1, 0, 0, -1, 0, 0, -1, 1],
                'winner' => GamePlayerEnum::PLAYER_TWO,
            ],

            "Player One Wins With Horizontal Line" => [
                'board' => [1, 1, 1, 0, -1, 0, -1, 0, 0],
                'winner' => GamePlayerEnum::PLAYER_ONE,
            ],

            "Player Two Wins With Horizontal Line" => [
                'board' => [0, 0, 1, 0, 1, 0, -1, -1, -1],
                'winner' => GamePlayerEnum::PLAYER_TWO,
            ],
        ];
    }
}