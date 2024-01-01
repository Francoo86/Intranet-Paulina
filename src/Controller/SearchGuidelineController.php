<?php

namespace App\Controller;

use App\Form\GuidelineType;
use App\Repository\GuidelineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchGuidelineController extends AbstractController
{
    #[Route('/search/guideline', name: 'app_search_guideline', methods: ['GET'])]
    public function index(GuidelineRepository $repo, Request $req, FormFactoryInterface $factory, EntityManagerInterface $entityManager): Response
    {
        //AJAX moment.
        $target = $req->get('target');
        $currentGuidelines = $repo->findByShowName($target);

        $allForms = [];

        foreach ($currentGuidelines as $guideline) {
            $formName = sprintf("guideline_%s", $guideline->getId());
            $form = $factory->createNamed($formName, GuidelineType::class, $guideline);
            $form->handleRequest($req);

            if ($req->getMethod() === "POST" && $req->request->has($formName)) {

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($guideline);
                    $entityManager->flush();
                }

                return $this->redirectToRoute('app_guideline_index');
            }

            $allForms[] = [
                'form' => $form->createView(),
            ];
        }

        return new JsonResponse($this->renderView("guideline/all_guidelines.html.twig", [
            'guidelines' => $currentGuidelines,
            'allForms' => $allForms,
        ]));
    }
}
