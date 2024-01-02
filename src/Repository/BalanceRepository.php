<?php

namespace App\Repository;

use App\Entity\Balance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Balance>
 *
 * @method Balance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Balance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Balance[]    findAll()
 * @method Balance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BalanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Balance::class);
    }

//    /**
//     * @return Balance[] Returns an array of Balance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Balance
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function getTotalAmount()
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('SUM(b.amount) as total_amount');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getActiveCount()
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('COUNT(b.id) as active_count')
           ->where('b.active = :active')
           ->setParameter('active', true);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getActiveCountLessThan(int $alertAmount)
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('COUNT(b.id) as active_count')
           ->where('b.active = :active')
           ->andWhere('b.amount < :alertAmount')
           ->setParameters([
               'active' => true,
               'alertAmount' => $alertAmount
           ]);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findTotalActiveBalanceAmount(): int
    {
        $qb = $this->createQueryBuilder('b');
    
        return $qb->select('SUM(b.amount)')
            ->where('b.active = :activeValue')
            ->setParameter('activeValue', true)
            ->getQuery()
            ->getSingleScalarResult();
    }
    
}
