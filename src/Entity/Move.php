<?php

namespace App\Entity;

use App\Repository\MoveRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MoveRepository::class)]
class Move
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
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
    private int $position;

    #[ORM\Column(type: Types::SMALLINT)]
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
    private int $player;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    public function __construct(Game $game, int $position, int $player)
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

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getPlayer(): ?int
    {
        return $this->player;
    }

    public function setPlayer(int $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
