<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\AudienceRepository;
use App\Repository\StockRepository;
use App\Repository\BalanceRepository;

class StatisticsController extends AbstractController
{
    #[Route('/statistics', name: 'app_statistics')]
    public function index(StockRepository $stockRepository, BalanceRepository $balanceRepository, AudienceRepository $audienceRepository): Response
    {
        //Renderizar todo lo que es demografia.
        $demographicsCount = $audienceRepository->getDemographicsCount();
        $locationCount = $audienceRepository->getLocationCount();

        //Stock
        $totalStockAmount = $stockRepository->findTotalActiveStockAmount() ?? '99';
        //$totalStockAmount = 99;
        $totalBalanceAmount = $balanceRepository->findTotalActiveBalanceAmount()?? '99';
        //$totalBalanceAmount = 99;
        $total = $totalStockAmount - $totalBalanceAmount;

        $activeCount = $balanceRepository->getActiveCount()?? '99';
        $alertAmount = 1000;
        $activeCritic = $balanceRepository->getActiveCountLessThan($alertAmount)?? '99';
        $activeNotCritCount = $activeCount - $activeCritic?? '99';


        return $this->render('statistics/index.html.twig', [
            'demographicsCount' => $demographicsCount,
            'locationCount' => $locationCount,

            //Stock y esas cosas.
            'totalStockAmount' => $totalStockAmount,
            'totalBalanceAmount' => $totalBalanceAmount,
            'total' => $total,
            'activeCount' => $activeCount,
            'activeCritic' => $activeCritic,
            'activeNotCritCount' => $activeNotCritCount
        ]);
    }
}
