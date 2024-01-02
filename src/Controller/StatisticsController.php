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
    public function index(): Response
    {
        return $this->render('statistics/index.html.twig', [
            'controller_name' => 'StatisticsController',
        ]);
    }

    #[Route('/statistics/audience', name: 'app_audience_stats', methods: ['GET'])]
    public function StatAudience(AudienceRepository $audienceRepository): Response
    {
        $demographicsCount = $audienceRepository->getDemographicsCount();
        $locationCount = $audienceRepository->getLocationCount();
    
        return $this->render('statistics/audience.html.twig', [
            'demographicsCount' => $demographicsCount,
            'locationCount' => $locationCount,
        ]);
    }

    #[Route('/statistics/publicity', name: 'app_publicity_stats', methods: ['GET'])]
    public function StatPublicity(StockRepository $stockRepository, BalanceRepository $balanceRepository): Response
    {
        $totalStockAmount = $stockRepository->findTotalActiveStockAmount() ?? '99';
        //$totalStockAmount = 99;
        $totalBalanceAmount = $balanceRepository->findTotalActiveBalanceAmount()?? '99';
        //$totalBalanceAmount = 99;
        $total = $totalStockAmount - $totalBalanceAmount;

        $activeCount = $balanceRepository->getActiveCount()?? '99';
        $alertAmount = 1000;
        $activeCritic = $balanceRepository->getActiveCountLessThan($alertAmount)?? '99';
        $activeNotCritCount = $activeCount - $activeCritic?? '99';   // No mezclar una activa con una activa+critica.
    
        return $this->render('statistics/publicity.html.twig', [
            'totalStockAmount' => $totalStockAmount,
            'totalBalanceAmount' => $totalBalanceAmount,
            'total' => $total,
            'activeCount' => $activeCount,
            'activeCritic' => $activeCritic,
            'activeNotCritCount' => $activeNotCritCount
        ]);
    }
}
