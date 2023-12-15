<?php

namespace App\Controller;

use App\Entity\Broadcaster;
use App\Form\BroadcasterType;
use App\Repository\BroadcasterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/broadcaster')]
class BroadcasterController extends AbstractController
{
    #[Route('/', name: 'app_broadcaster_index', methods: ['GET'])]
    public function index(BroadcasterRepository $broadcasterRepository): Response
    {
        return $this->render('broadcaster/index.html.twig', [
            'broadcasters' => $broadcasterRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_broadcaster_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $broadcaster = new Broadcaster();
        $form = $this->createForm(BroadcasterType::class, $broadcaster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($broadcaster);
            $entityManager->flush();

            return $this->redirectToRoute('app_broadcaster_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('broadcaster/new.html.twig', [
            'broadcaster' => $broadcaster,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_broadcaster_show', methods: ['GET'])]
    public function show(Broadcaster $broadcaster): Response
    {
        return $this->render('broadcaster/show.html.twig', [
            'broadcaster' => $broadcaster,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_broadcaster_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Broadcaster $broadcaster, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BroadcasterType::class, $broadcaster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_broadcaster_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('broadcaster/edit.html.twig', [
            'broadcaster' => $broadcaster,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_broadcaster_delete', methods: ['POST'])]
    public function delete(Request $request, Broadcaster $broadcaster, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$broadcaster->getId(), $request->request->get('_token'))) {
            $entityManager->remove($broadcaster);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_broadcaster_index', [], Response::HTTP_SEE_OTHER);
    }
}