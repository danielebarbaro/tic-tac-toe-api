<?php

namespace App\Enum;

enum GameStatusEnum: string
{
    case NEW = 'NEW';
    case ONGOING = 'ONGOING';
    case WON = 'WON';
    case TIE = 'TIE';
}
