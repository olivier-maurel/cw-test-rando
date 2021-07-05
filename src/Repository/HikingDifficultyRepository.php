<?php

namespace App\Repository;

use App\Entity\HikingDifficulty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HikingDifficulty|null find($id, $lockMode = null, $lockVersion = null)
 * @method HikingDifficulty|null findOneBy(array $criteria, array $orderBy = null)
 * @method HikingDifficulty[]    findAll()
 * @method HikingDifficulty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HikingDifficultyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HikingDifficulty::class);
    }

    // /**
    //  * @return HikingDifficulty[] Returns an array of HikingDifficulty objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HikingDifficulty
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
