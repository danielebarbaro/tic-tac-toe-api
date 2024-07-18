<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Move;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Move>
 */
class MoveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Move::class);
    }

    public function save(Move $move): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($move);
        $entityManager->flush();
    }

    public function getMovesByGameIdAndPlayer(Game $game, int $player): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.game = :game')
            ->andWhere('m.player = :player')
            ->getQuery()
            ->getResult();
    }

    public function countMovesByGameIdAndPlayer(Game $game, int $player): int
    {
        return $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->andWhere('m.game = :game')
            ->andWhere('m.player = :player')
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return Move[] Returns an array of Move objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Move
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
