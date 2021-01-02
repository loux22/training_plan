<?php

namespace App\Repository;

use App\Entity\WentOut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WentOut|null find($id, $lockMode = null, $lockVersion = null)
 * @method WentOut|null findOneBy(array $criteria, array $orderBy = null)
 * @method WentOut[]    findAll()
 * @method WentOut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WentOutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WentOut::class);
    }

    // /**
    //  * @return WentOut[] Returns an array of WentOut objects
    //  */
    
    public function findWentoutOrderByDate($user)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.user = :val')
            ->setParameter('val', $user)
            ->orderBy('w.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findWentoutOrderByDateDesc($user)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.user = :val')
            ->setParameter('val', $user)
            ->orderBy('w.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findWeekOrderByDate($week)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.week = :val')
            ->setParameter('val', $week)
            ->orderBy('w.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?WentOut
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
