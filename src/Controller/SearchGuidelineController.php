<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchGuidelineController extends AbstractController
{
    #[Route('/search/guideline', name: 'app_search_guideline', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('search_guideline/index.html.twig', [
            'controller_name' => 'SearchGuidelineController',
        ]);
    }
}
