<?php

namespace App\Dto;

use App\Enum\GamePlayerEnum;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "MoveCreateDto",
    title: "MoveCreateDto",
    description: "Move create DTO",
    type: "object",
)]
final class MoveCreateDto
{
    #[Groups(["move:write", "move:read"])]
    #[Assert\NotBlank(groups: ["move:write"])]
    #[OA\Property(
        description: "Player making the move",
        type: "string",
        enum: ["PLAYER_ONE", "PLAYER_TWO"]
    )]
    public GamePlayerEnum $player;

    #[Groups(["move:write", "move:read"])]
    #[Assert\NotBlank(groups: ["move:write"])]
    #[Assert\Range(min: 1, max: 9, groups: ["move:write"])]
    #[OA\Property(
        description: "Position on the game board",
        type: "integer",
        example: 1
    )]
    public int $position;
}