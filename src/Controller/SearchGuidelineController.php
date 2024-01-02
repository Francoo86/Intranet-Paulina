<?php

namespace App\Controller;

use App\Form\GuidelineType;
use App\Form\ShowType;
use App\Repository\GuidelineRepository;
use App\Repository\ShowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/search')]
class SearchGuidelineController extends AbstractController
{
    #[Route('/guideline', name: 'app_search_guideline', methods: ['GET'])]
    public function index(GuidelineRepository $repo, Request $req, FormFactoryInterface $factory, EntityManagerInterface $entityManager): Response
    {
        if(!$req->isXmlHttpRequest()){
            return new JsonResponse(["message" => "Lo que usted busca se encuentra en la ruta /guideline."]);
        }
    
        //AJAX moment.
        $target = $req->get('target');

        $currentGuidelines = $repo->findByGuidelineName($target);

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

    #[Route('/show', name: 'app_search_show', methods: ['GET'])]
    public function searchShow(ShowRepository $repo, Request $req, FormFactoryInterface $factory, EntityManagerInterface $entityManager): Response
    {
        if(!$req->isXmlHttpRequest()){
            return new JsonResponse(["message" => "Lo que usted busca se encuentra en la ruta /show/."]);
        }
        //AJAX moment.
        $target = $req->get('target');
        $currentShows = $repo->findByShowName($target);//$repo->findByShowName($target);

        $allForms = [];

        foreach ($currentShows as $show) {
            $formName = sprintf("shows_%s", $show->getId());
            $form = $factory->createNamed($formName, ShowType::class, $show);
            $form->handleRequest($req);

            if ($req->getMethod() === "POST" && $req->request->has($formName)) {

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($show);
                    $entityManager->flush();
                }

                return $this->redirectToRoute('app_show_index');
            }

            $allForms[] = [
                'form' => $form->createView(),
            ];
        }

        return new JsonResponse($this->renderView("show/all_shows.html.twig", [
            'shows' => $currentShows,
            'allForms' => $allForms,
        ]));
    }

    #[Route('/publicity', name: 'app_search_publicity', methods: ['GET'])]
    public function searchPublicity(ShowRepository $repo, Request $req, FormFactoryInterface $factory, EntityManagerInterface $entityManager): Response
    {
        if(!$req->isXmlHttpRequest()){
            return new JsonResponse(["message" => "Lo que usted busca se encuentra en la ruta /show/."]);
        }
        //AJAX moment.
        $target = $req->get('target');
        $currentShows = $repo->findByShowName($target);//$repo->findByShowName($target);

        $allForms = [];

        foreach ($currentShows as $show) {
            $formName = sprintf("shows_%s", $show->getId());
            $form = $factory->createNamed($formName, ShowType::class, $show);
            $form->handleRequest($req);

            if ($req->getMethod() === "POST" && $req->request->has($formName)) {

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($show);
                    $entityManager->flush();
                }

                return $this->redirectToRoute('app_show_index');
            }

            $allForms[] = [
                'form' => $form->createView(),
            ];
        }

        return new JsonResponse($this->renderView("show/all_shows.html.twig", [
            'shows' => $currentShows,
            'allForms' => $allForms,
        ]));
    }
}
