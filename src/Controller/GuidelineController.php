<?php

namespace App\Controller;

use App\Entity\Guideline;
use App\Form\GuidelineType;
use App\Repository\GuidelineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/guideline')]
class GuidelineController extends AbstractController
{
    #[Route('/', name: 'app_guideline_index', methods: ['GET'])]
    public function index(GuidelineRepository $guidelineRepository): Response
    {
        return $this->render('guideline/index.html.twig', [
            'guidelines' => $guidelineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_guideline_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $guideline = new Guideline();
        $form = $this->createForm(GuidelineType::class, $guideline);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($guideline);
            $entityManager->flush();

            return $this->redirectToRoute('app_guideline_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('guideline/new.html.twig', [
            'guideline' => $guideline,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_guideline_show', methods: ['GET'])]
    public function show(Guideline $guideline): Response
    {
        return $this->render('guideline/show.html.twig', [
            'guideline' => $guideline,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_guideline_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Guideline $guideline, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GuidelineType::class, $guideline);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_guideline_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('guideline/edit.html.twig', [
            'guideline' => $guideline,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_guideline_delete', methods: ['POST'])]
    public function delete(Request $request, Guideline $guideline, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$guideline->getId(), $request->request->get('_token'))) {
            $entityManager->remove($guideline);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_guideline_index', [], Response::HTTP_SEE_OTHER);
    }
}
