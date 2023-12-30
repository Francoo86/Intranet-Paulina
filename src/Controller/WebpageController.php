<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class WebpageController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/menu', name: 'app_webpage')]
    public function index(): Response
    {
        // Obtén el objeto User
        $user = $this->security->getUser();

        return $this->render('webpage/index.html.twig', [
            'user' => $user,
        ]);
    }
}


