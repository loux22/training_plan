<?php

namespace App\Repository;

use App\Entity\WeekYears;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WeekYears|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeekYears|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeekYears[]    findAll()
 * @method WeekYears[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeekYearsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeekYears::class);
    }

    // /**
    //  * @return WeekYears[] Returns an array of WeekYears objects
    //  */

    public function findWeekOrderByDate($user)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.user = :val')
            ->setParameter('val', $user)
            ->addOrderBy('w.week', 'ASC')
            ->addOrderBy('w.years', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
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
    public function findOneBySomeField($value): ?WeekYears
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
