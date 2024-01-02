<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Query\Cast;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customer>
 *
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function findByCustomerRut($value): array
    {
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomNumericFunction('CAST', Cast::class);
    
        return $this->createQueryBuilder('s')
            ->where('CAST(s.rut as TEXT) LIKE :val')
            //->setParameter('val', $value)
            ->andWhere("s.DeletedAt IS NULL")
            ->setParameter('val', '%'.strtolower($value).'%')
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Customer[] Returns an array of Customer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Customer
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
