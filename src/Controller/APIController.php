<?php

namespace App\Controller;

use App\Helper;
use App\Repository\ManagerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class APIController extends AbstractController
{
    #[Route('/', name: 'api')]
    public function index(): Response
    {
        return $this->json(['message' => 'Here be dragons!']);
    }
    
    #[Route('/manager', name: 'api_manager', methods: ['GET'])]
    public function APIManagers(ManagerRepository $managerRepository, SerializerInterface $serial): Response
    {
        $allManagers = $managerRepository->findAll();
    
        $jsonObject = $serial->serialize($allManagers, 'json', [    
            'circular_reference_handler' => function ($object) {
            return $object->getId();
        }]);

        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }
}
