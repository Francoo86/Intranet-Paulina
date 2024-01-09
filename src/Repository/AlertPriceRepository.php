<?php

namespace App\Repository;

use App\Entity\AlertPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AlertPrice>
 *
 * @method AlertPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlertPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlertPrice[]    findAll()
 * @method AlertPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlertPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AlertPrice::class);
    }

    public function findTheOnlyRow()
    {
        return $this->createQueryBuilder('e')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return AlertPrice[] Returns an array of AlertPrice objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AlertPrice
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
