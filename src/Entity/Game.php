<?php

namespace App\Entity;

use App\Enum\GameLevelEnum;
use App\Enum\GamePlayerEnum;
use App\Enum\GameStatusEnum;
use App\Repository\GameRepository;
use App\Validator\CheckPlayerUpdate;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[OA\Schema(
    title: "Game",
    description: "Represents a game of tic-tac-toe.",
    required: ["status", "level", "board", "players"]
)]
class Game
{
    public const BOARD_INDEX = 1;
    public const BOARD_SIZE = 9;
    public const MAX_PLAYER_ONE_MOVES = 5;
    public const MAX_PLAYER_TWO_MOVES = 4;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[OA\Property(
        description: 'The unique identifier of the game.',
        type: 'uuid'
    )]
    #[Groups(['game:read', 'game:write'])]
    private Uuid $id;

    #[ORM\Column(type: Types::STRING, nullable: false, enumType: GameStatusEnum::class)]
    #[Assert\NotNull]
    #[Assert\Choice([
        GameStatusEnum::NEW,
        GameStatusEnum::ONGOING,
        GameStatusEnum::WON,
        GameStatusEnum::TIE
    ])]
    #[OA\Property(
        description: 'The status of the game.',
        type: 'string',
        enum: [
            GameStatusEnum::NEW,
            GameStatusEnum::ONGOING,
            GameStatusEnum::WON,
            GameStatusEnum::TIE
        ]
    )]
    #[Groups(['game:read'])]
    private GameStatusEnum $status;

    #[ORM\Column(type: Types::STRING, nullable: false, enumType: GameLevelEnum::class)]
    #[Assert\NotNull]
    #[Assert\Choice([
        GameLevelEnum::NEWBIE,
        GameLevelEnum::GOOD
    ])]
    #[OA\Property(
        description: 'The level of the game.',
        type: 'string',
        enum: [
            GameLevelEnum::NEWBIE,
            GameLevelEnum::GOOD
        ]
    )]
    #[Groups(['game:read'])]
    private GameLevelEnum $level;

    #[ORM\Column(type: Types::ARRAY)]
    #[Assert\NotNull]
    #[Assert\Count(
        min: self::BOARD_SIZE,
        max: self::BOARD_SIZE
    )]
    #[OA\Property(
        description: 'The board of the game.',
        type: 'array',
        example: [0, 0, 0, 0, 0, 0, 0, 0, 0]
    )]
    #[Groups(['game:read', 'game:write'])]
    private array $board = [];

    #[ORM\Column(nullable: true, enumType: GamePlayerEnum::class)]
    #[Assert\Choice([
        1,
        2
    ])]

    #[OA\Property(
        description: 'The winner of the game.',
        type: 'boolean'
    )]
    #[Groups(['game:read'])]
    private ?GamePlayerEnum $winner = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotNull]
    #[Assert\Positive]
    #[Assert\Choice([
        1,
        2
    ])]
    #[Assert\GreaterThanOrEqual(
        value: 1,
    )]
    #[Assert\LessThanOrEqual(
        value: 2,
    )]
    #[CheckPlayerUpdate]
    #[OA\Property(
        description: 'The number of players in the game.',
        type: 'integer'
    )]
    #[Groups(['game:read', 'game:write'])]
    private int $players;

    #[ORM\Column(nullable: true)]
    #[OA\Property(
        description: 'The date and time when the game was completed.',
        type: 'string',
        format: 'date-time'
    )]
    #[Groups(['game:read'])]
    private ?DateTimeImmutable $gameCompletedAt = null;

    #[ORM\Column]
    #[OA\Property(
        description: 'The date and time when the game was created.',
        type: 'string',
        format: 'date-time'
    )]
    private DateTimeImmutable $createdAt;

    /**
     * @var Collection<int, Move>
     */
    #[ORM\OneToMany(targetEntity: Move::class, mappedBy: 'game', orphanRemoval: true)]
    private Collection $moves;

    public function __construct(int $players)
    {
        $this->players = $players;

        $this->status = GameStatusEnum::NEW;
        $this->level = GameLevelEnum::NEWBIE;

        $this->moves = new ArrayCollection();

        $this->board = array_fill(
            0,
            self::BOARD_SIZE,
            0);

        $this->createdAt = new DateTimeImmutable('now');
    }
    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getStatus(): GameStatusEnum
    {
        return $this->status;
    }

    public function setStatus(GameStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getLevel(): GameLevelEnum
    {
        return $this->level;
    }

    public function setLevel(GameLevelEnum $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getBoard(): array
    {
        return $this->board;
    }

    public function setBoard(array $board): static
    {
        $this->board = $board;

        return $this;
    }

    public function getWinner(): ?GamePlayerEnum
    {
        return $this->winner;
    }

    public function setWinner(?GamePlayerEnum $winner): static
    {
        $this->winner = $winner;

        return $this;
    }

    public function getPlayers(): int
    {
        return $this->players;
    }

    public function setPlayers(int $players): static
    {
        $this->players = $players;

        return $this;
    }

    public function getGameCompletedAt(): ?\DateTimeImmutable
    {
        return $this->gameCompletedAt;
    }

    public function setGameCompletedAt(?\DateTimeImmutable $gameCompletedAt): static
    {
        $this->gameCompletedAt = $gameCompletedAt;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return Collection<int, Move>
     */
    public function getMoves(): Collection
    {
        return $this->moves;
    }

    public function addMove(Move $move): static
    {
        if (!$this->moves->contains($move)) {
            $this->moves->add($move);
            $move->setGame($this);
        }

        return $this;
    }

    public function removeMove(Move $move): static
    {
        if ($this->moves->removeElement($move)) {
            // set the owning side to null (unless already changed)
            if ($move->getGame() === $this) {
                $move->setGame(null);
            }
        }

        return $this;
    }

    public function canPlay(): bool
    {
        return
            $this->getStatus() === GameStatusEnum::ONGOING &&
            ($this->getMoves()->isEmpty() || count($this->getMoves()) <= Game::BOARD_SIZE);
    }

    public function isBoardFull(): bool
    {
        return count(array_filter($this->getBoard())) === Game::BOARD_SIZE;
    }
}
