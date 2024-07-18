<?php

namespace App\Entity;

use App\Enum\GameLevelEnum;
use App\Enum\GameStatusEnum;
use App\Repository\GameRepository;
use App\Validator\CheckPlayerUpdate;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    public const BOARD_INDEX = 0;
    public const BOARD_SIZE = 9;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\Column(type: Types::STRING, nullable: false, enumType: GameStatusEnum::class)]
    #[Assert\NotNull]
    #[Assert\Choice([
        GameStatusEnum::NEW,
        GameStatusEnum::ONGOING,
        GameStatusEnum::WON,
        GameStatusEnum::TIE
    ])]
    private GameStatusEnum $status;

    #[ORM\Column(type: Types::STRING, nullable: false, enumType: GameLevelEnum::class)]
    #[Assert\NotNull]
    #[Assert\Choice([
        GameLevelEnum::NEWBIE,
        GameLevelEnum::GOOD
    ])]
    private GameLevelEnum $level;

    #[ORM\Column(type: Types::ARRAY)]
    #[Assert\NotNull]
    private array $board = [];

    #[ORM\Column(nullable: true)]
    private ?bool $winner = null;

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
    // #[CheckPlayerUpdate]
    private ?int $players = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $gameCompletedAt = null;

    #[ORM\Column]
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
            self::BOARD_INDEX,
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

    public function isWinner(): ?bool
    {
        return $this->winner;
    }

    public function setWinner(?bool $winner): static
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
}
