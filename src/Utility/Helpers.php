<?php

namespace App;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class Helper {
    public const POST_METHOD = "POST";

    public static function GetVerifierDigit(int $rut) : string
    {
        $s = 1;
        for ($m = 0; $rut != 0; $rut = intval($rut / 10)) {
            $s = ($s + $rut % 10 * (9 - $m++ % 6)) % 11;
        }
        return chr($s ? $s + 47 : 75);
    }

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