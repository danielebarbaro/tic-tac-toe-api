<?php

namespace App\Entity;

use App\Enum\GamePlayerEnum;
use App\Enum\GameStatusEnum;
use App\Repository\MoveRepository;
use App\Validator\CheckValidPosition;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

#[ORM\Entity(repositoryClass: MoveRepository::class)]
#[OA\Schema(
    title: "Move",
    description: "Represents a move in the game.",
    required: ["game", "position", "player"]
)]
class Move
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[OA\Property(
        description: 'The unique identifier of the move.',
        type: 'uuid'
    )]
    #[Groups(['move:read'])]
    private Uuid $id;

    #[ORM\ManyToOne(inversedBy: 'moves')]
    #[ORM\JoinColumn(nullable: false)]
    private Game $game;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotNull]
    #[Assert\GreaterThanOrEqual(
        value: Game::BOARD_INDEX,
    )]
    #[Assert\LessThanOrEqual(
        value: Game::BOARD_SIZE,
    )]
    #[OA\Property(
        description: 'The position of the move on the board.',
        type: 'integer'
    )]
    #[CheckValidPosition]
    #[Groups(['move:read'])]
    private int $position;

    #[ORM\Column(type: Types::SMALLINT, enumType: GamePlayerEnum::class)]
    #[Assert\NotNull]
    #[Assert\Choice([
        GamePlayerEnum::PLAYER_ONE,
        GamePlayerEnum::PLAYER_TWO,
    ])]
    #[OA\Property(
        description: 'The player who made the move.',
        type: 'integer'
    )]
    #[Groups(['move:read'])]
    private GamePlayerEnum $player;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    public function __construct(Game $game, int $position, GamePlayerEnum $player)
    {
        $this->game = $game;
        $this->position = $position;
        $this->player = $player;

        $this->createdAt = new DateTimeImmutable('now');
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function getPlayer(): GamePlayerEnum
    {
        return $this->player;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
