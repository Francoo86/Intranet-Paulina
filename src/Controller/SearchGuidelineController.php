<?php

namespace App\Controller;

use App\Form\GuidelineType;
use App\Form\PublicityType;
use App\Form\ShowType;
use App\Repository\GuidelineRepository;
use App\Repository\PublicityRepository;
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

    //This function is more different than the above ones because it will search two.
    #[Route('/publicity', name: 'app_search_publicity', methods: ['GET'])]
    public function searchPublicity(PublicityRepository $repo, Request $req, FormFactoryInterface $factory, EntityManagerInterface $entityManager): Response
    {
        if(!$req->isXmlHttpRequest()){
            return new JsonResponse(["message" => "Lo que usted busca se encuentra en la ruta /publicity/."]);
        }
        //AJAX moment.
        //Use 2 fields this time because yes.
        $customer = $req->get('customer');
        $guideline = $req->get('guideline');

        $currentPublicities = $repo->findByCustomerAndGuideline($customer, $guideline);

        $allForms = [];

        foreach ($currentPublicities as $pub) {
            $formName = sprintf("publicity_%s", $pub->getId());
            $form = $factory->createNamed($formName, PublicityType::class, $pub);
            $form->handleRequest($req);

            if ($req->getMethod() === "POST" && $req->request->has($formName)) {

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($pub);
                    $entityManager->flush();
                }

                return $this->redirectToRoute('app_publicity_index');
            }

            $allForms[] = [
                'form' => $form->createView(),
            ];
        }

        return new JsonResponse($this->renderView("publicity/all_publicities.html.twig", [
            'publicities' => $currentPublicities,
            'allForms' => $allForms,
        ]));
    }
}
