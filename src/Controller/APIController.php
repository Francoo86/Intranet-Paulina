<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class APIController extends AbstractController
{
    #[Route('/a/p/i', name: 'app_a_p_i')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'APIController',
        ]);
    }
}
