<?php

namespace App\Controller;

use App\Entity\Summary;
use App\Form\SummaryType;
use App\Repository\SummaryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/summary')]
class SummaryController extends AbstractController
{
    #[Route('/', name: 'app_summary_index', methods: ['GET'])]
    public function index(SummaryRepository $summaryRepository): Response
    {
        return $this->render('summary/index.html.twig', [
            'summaries' => $summaryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_summary_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $summary = new Summary();
        $form = $this->createForm(SummaryType::class, $summary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($summary);
            $entityManager->flush();

            return $this->redirectToRoute('app_summary_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('summary/new.html.twig', [
            'summary' => $summary,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_summary_show', methods: ['GET'])]
    public function show(Summary $summary): Response
    {
        return $this->render('summary/show.html.twig', [
            'summary' => $summary,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_summary_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Summary $summary, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SummaryType::class, $summary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_summary_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('summary/edit.html.twig', [
            'summary' => $summary,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_summary_delete', methods: ['POST'])]
    public function delete(Request $request, Summary $summary, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$summary->getId(), $request->request->get('_token'))) {
            $entityManager->remove($summary);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_summary_index', [], Response::HTTP_SEE_OTHER);
    }
}
