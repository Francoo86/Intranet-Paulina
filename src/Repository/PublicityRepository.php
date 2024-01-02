<?php

namespace App\Repository;

use App\Entity\Publicity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Publicity>
 *
 * @method Publicity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publicity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publicity[]    findAll()
 * @method Publicity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publicity::class);
    }

    //La función más bacan de este sistema.
    public function findByCustomerAndGuideline(string $clientName, string $guideline): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere("s.DeletedAt IS NULL")
            ->join('s.customer', 'c')
            ->where('LOWER(c.name) LIKE :customer')
            //->setParameter('val', $value)
            ->setParameter('customer', '%'.strtolower($clientName).'%')
            ->join('s.Guideline', 'g')
            ->andWhere('LOWER(g.show_name) LIKE :guideline')
            ->setParameter('guideline', '%'.strtolower($guideline).'%')
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Publicity[] Returns an array of Publicity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Publicity
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
