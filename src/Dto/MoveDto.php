<?php

namespace App\Dto;

use App\Enum\GamePlayerEnum;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

final class MoveDto
{
    #[Groups(['move:write', 'move:read'])]
    #[Assert\NotBlank(groups: ['move:write'])]
    public Uuid $id;

    #[Groups(['move:write', 'move:read'])]
    #[Assert\NotBlank(groups: ['move:write'])]
    public GamePlayerEnum $player;

    #[Groups(['move:write', 'move:read'])]
    #[Assert\NotBlank(groups: ['move:write'])]
    #[Assert\Range(min: 1, max: 9, groups: ['move:write'])]
    public int $position;

    #[Groups(['move:write', 'move:read'])]
    #[Assert\NotBlank(groups: ['move:write'])]
    #[Assert\Choice(choices: [1, 2], groups: ['move:write'])]
    public GamePlayerEnum $nextPlayer;

    #[Groups(['move:write', 'move:read'])]
    #[Assert\NotBlank(groups: ['move:write'])]
    public bool $isGameOver = false;

    #[Groups(['move:write', 'move:read'])]
    public ?GamePlayerEnum $winner = null;
}
