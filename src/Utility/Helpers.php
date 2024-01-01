<?php

namespace App;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class Helper {
    public static function AddSimilarForms(ServiceEntityRepository $repo, Request $req, EntityManagerInterface $manager){

    }

    public static function FindAllOrderedById(ServiceEntityRepository $repo){
        return $repo->createQueryBuilder('e')
        ->andWhere('e.DeletedAt IS NULL')
        ->orderBy('e.id', 'ASC')
        ->getQuery()
        ->getResult();
    }
}

?>