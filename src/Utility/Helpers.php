<?php

namespace App;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class Helper {
    public const POST_METHOD = "POST";
    //Returns wrapper, what could i ask more.
    public static function GetNonSoftDeletedInForms() {
        return function (EntityRepository $er): QueryBuilder {
            return $er->createQueryBuilder('g')
                ->where('g.DeletedAt is NULL');
        };
    }

    public static function FindAllOrderedById(ServiceEntityRepository $repo){
        return $repo->createQueryBuilder('e')
        ->andWhere('e.DeletedAt IS NULL')
        ->orderBy('e.id', 'ASC')
        ->getQuery()
        ->getResult();
    }

    public static function FindAllAscendant(ServiceEntityRepository $repo) : array {
        return $repo->createQueryBuilder('e')
        ->orderBy('e.id', 'ASC')
        ->getQuery()
        ->getResult();
    }
}

?>