<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DTEController extends AbstractController
{
    #[Route('/dte', name: 'app_d_t_e')]
    public function index(): Response
    {
        return $this->render('dte/index.html.twig', [
            'controller_name' => 'DTEController',
        ]);
    }
}
