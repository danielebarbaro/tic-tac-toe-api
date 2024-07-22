<?php

namespace App\Service;

use App\Enum\GamePlayerEnum;

class CheckWinnerService
{
    public const MAX_ELEMENTS = 3;

    public function execute(array $board): ?GamePlayerEnum
    {
        $matrixBoard = array_chunk($board, self::MAX_ELEMENTS);

        $rowSums = [0, 0, 0];
        $colSums = [0, 0, 0];
        $diagonal1Sum = 0;
        $diagonal2Sum = 0;

        $range = range(0, self::MAX_ELEMENTS - 1);

        /*
         * 0,0 0,1 0,2
         * 1,0 1,1 1,2
         * 2,0 2,1 2,2
         */
        foreach ($range as $i) {
            foreach ($range as $j) {
                $rowSums[$i] += $matrixBoard[$i][$j];
                $colSums[$j] += $matrixBoard[$i][$j];

                if ($i == $j) {
                    $diagonal1Sum += $matrixBoard[$i][$j];
                }
                if ($i + $j == 2) {
                    $diagonal2Sum += $matrixBoard[$i][$j];
                }
            }
        }

        $sums = array_merge($rowSums, $colSums, [$diagonal1Sum, $diagonal2Sum]);
        foreach ($sums as $sum) {
            switch ($sum) {
                case 3:
                    return GamePlayerEnum::PLAYER_ONE;
                case -3:
                    return GamePlayerEnum::PLAYER_TWO;
            }
        }

        return null;
    }
}
