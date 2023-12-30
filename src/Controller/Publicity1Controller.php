<?php

namespace App\Controller;

use App\Entity\Publicity;
use App\Form\Publicity1Type;
use App\Repository\PublicityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/publicity1')]
class Publicity1Controller extends AbstractController
{
    #[Route('/', name: 'app_publicity1_index', methods: ['GET'])]
    public function index(PublicityRepository $publicityRepository): Response
    {
        return $this->render('publicity1/index.html.twig', [
            'publicities' => $publicityRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_publicity1_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publicity = new Publicity();
        $form = $this->createForm(Publicity1Type::class, $publicity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($publicity);
            $entityManager->flush();

            return $this->redirectToRoute('app_publicity1_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publicity1/new.html.twig', [
            'publicity' => $publicity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publicity1_show', methods: ['GET'])]
    public function show(Publicity $publicity): Response
    {
        return $this->render('publicity1/show.html.twig', [
            'publicity' => $publicity,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_publicity1_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publicity $publicity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Publicity1Type::class, $publicity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_publicity1_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publicity1/edit.html.twig', [
            'publicity' => $publicity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publicity1_delete', methods: ['POST'])]
    public function delete(Request $request, Publicity $publicity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publicity->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publicity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publicity1_index', [], Response::HTTP_SEE_OTHER);
    }
}
