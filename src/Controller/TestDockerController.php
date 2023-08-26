<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestDockerController extends AbstractController
{
    #[Route('/test/docker', name: 'app_test_docker')]
    public function index(): Response
    {
        return $this->render('test_docker/index.html.twig', [
            'controller_name' => 'TestDockerController',
        ]);
    }
}
