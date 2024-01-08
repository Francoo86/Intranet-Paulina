<?php

namespace App\Repository;

use App\Entity\Audience;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Query\Cast;

/**
 * @extends ServiceEntityRepository<Audience>
 *
 * @method Audience|null find($id, $lockMode = null, $lockVersion = null)
 * @method Audience|null findOneBy(array $criteria, array $orderBy = null)
 * @method Audience[]    findAll()
 * @method Audience[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AudienceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Audience::class);
    }

    public function getDemographicsCount(): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->leftJoin('a.publicities', 'p')
           ->leftJoin('p.Stock', 's')
           ->leftJoin('s.balance', 'b')
           ->where('b.active = :active')
           ->setParameter('active', true);

        return $qb->select('a.demography, COUNT(a.id) as count')
            ->groupBy('a.demography')
            ->getQuery()
            ->getArrayResult();
    }

    public function getLocationCount(): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->leftJoin('a.publicities', 'p')
           ->leftJoin('p.Stock', 's')
           ->leftJoin('s.balance', 'b')
           ->where('b.active = :active')
           ->setParameter('active', true);

        return $qb->select('a.locality, COUNT(a.id) as count')
            ->groupBy('a.locality')
            ->getQuery()
            ->getArrayResult();
    }

    public function findByAudienceDemography($value): array
    {
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomNumericFunction('CAST', Cast::class);

        return $this->createQueryBuilder('a')
            //->where('CAST(a.demography as TEXT) LIKE :val')
            ->where('LOWER(a.demography) LIKE :val')
            ->andWhere("a.DeletedAt IS NULL")
            ->setParameter('val', '%'.strtolower($value).'%')
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
    }

    public function findByAudienceLocation($value): array
    {
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomNumericFunction('CAST', Cast::class);

        return $this->createQueryBuilder('a')
            ->where('CAST(a.locality as TEXT) LIKE :val')
            ->andWhere("a.DeletedAt IS NULL")
            ->setParameter('val', '%'.strtolower($value).'%')
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
    }

    public function findByMultipleFields($value): array
    {
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomNumericFunction('CAST', Cast::class);
    
        return $this->createQueryBuilder('a')
            ->where('CAST(a.demography as TEXT) LIKE :val')
            ->orWhere('CAST(a.locality as TEXT) LIKE :val')
            ->orWhere('CAST(a.type as TEXT) LIKE :val')
            ->andWhere("a.DeletedAt IS null")
            ->setParameter('val', '%'.strtolower($value).'%')
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Audience[] Returns an array of Audience objects
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

//    public function findOneBySomeField($value): ?Audience
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
