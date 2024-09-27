<?php

namespace App\Repository;

use App\Entity\Move;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
}
