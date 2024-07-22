<?php

namespace App\Dto;

use App\Enum\GameLevelEnum;
use App\Enum\GamePlayerEnum;
use App\Enum\GameStatusEnum;
use App\Validator\CheckPlayerUpdate;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

final class GameDto
{
    #[Groups(['game:read'])]
    #[Assert\NotBlank(groups: ['move:write'])]
    public Uuid $id;

    #[Assert\NotNull]
    #[Assert\Choice([
        GameStatusEnum::NEW,
        GameStatusEnum::ONGOING,
        GameStatusEnum::WON,
        GameStatusEnum::TIE,
    ])]
    #[Groups(['game:read'])]
    private GameStatusEnum $status;

    #[Assert\NotNull]
    #[Assert\Choice([
        GameLevelEnum::NEWBIE,
        GameLevelEnum::GOOD,
    ])]
    #[Groups(['game:read'])]
    private GameLevelEnum $level;

    #[Assert\NotNull]
    #[Assert\Count(
        min: self::BOARD_SIZE,
        max: self::BOARD_SIZE
    )]
    #[Groups(['game:read'])]
    private array $board = [];

    #[Assert\Choice([
        1,
        2,
    ])]
    #[Groups(['game:read'])]
    private ?GamePlayerEnum $winner = null;

    #[Assert\NotNull]
    #[Assert\Positive]
    #[Assert\Choice([
        1,
        2,
    ])]
    #[Assert\GreaterThanOrEqual(
        value: 1,
    )]
    #[Assert\LessThanOrEqual(
        value: 2,
    )]
    #[CheckPlayerUpdate]
    #[Groups(['game:read'])]
    private int $players;

    #[Groups(['game:read'])]
    private ?DateTimeImmutable $gameCompletedAt = null;
}
