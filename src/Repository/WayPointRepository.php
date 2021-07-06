<?php

namespace App\Repository;

use App\Entity\WayPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WayPoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method WayPoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method WayPoint[]    findAll()
 * @method WayPoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WayPointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WayPoint::class);
    }

    // /**
    //  * @return WayPoint[] Returns an array of WayPoint objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WayPoint
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
