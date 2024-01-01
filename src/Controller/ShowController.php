<?php

namespace App\Controller;

use App\Entity\Show;
use App\Form\ShowType;
use App\Repository\ShowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/show')]
class ShowController extends AbstractController
{
    #[Route('/', name: 'app_show_index', methods: ['GET'])]
    public function index(ShowRepository $showRepository): Response
    {
        //dd($showRepository->findAll());
        return $this->render('show/index.html.twig', [
            'shows' => $showRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_show_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $show = new Show();
        $form = $this->createForm(ShowType::class, $show);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($show);
            $entityManager->flush();

            return $this->redirectToRoute('app_show_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('show/new.html.twig', [
            'show' => $show,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_show_show', methods: ['GET'])]
    public function show(Show $show): Response
    {
        return $this->render('show/show.html.twig', [
            'show' => $show,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_show_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Show $show, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ShowType::class, $show);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_show_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('show/edit.html.twig', [
            'show' => $show,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_show_delete', methods: ['POST'])]
    public function delete(Request $request, Show $show, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$show->getId(), $request->request->get('_token'))) {
            $entityManager->remove($show);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_show_index', [], Response::HTTP_SEE_OTHER);
    }
}
