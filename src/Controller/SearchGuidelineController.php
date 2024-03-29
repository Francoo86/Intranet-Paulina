<?php

namespace App\Controller;

use App\Form\BroadcasterType;
use App\Form\CustomerType;
use App\Form\GuidelineType;
use App\Form\PublicityType;
use App\Form\ShowType;
use App\Form\AudienceType;
use App\Repository\BroadcasterRepository;
use App\Repository\CustomerRepository;
use App\Repository\GuidelineRepository;
use App\Repository\PublicityRepository;
use App\Repository\ShowRepository;
use App\Repository\AudienceRepository;
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
        //AJAX moment.
        //Use 2 fields this time because yes.
        $customer = $req->get('customer');
        $guideline = $req->get('guideline');

        $currentPublicities = $repo->findByCustomerAndGuideline($customer, $guideline);

        $allForms = [];

        foreach ($currentPublicities as $pub) {
            //dd($pub);
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

    #[Route('/customer', name: 'app_search_customer', methods: ['GET'])]
    public function searchCustomer(CustomerRepository $repo, Request $req, FormFactoryInterface $factory, EntityManagerInterface $entityManager): Response
    {
        // AJAX moment.
        $target = $req->get('target');
        $currentCustomers = $repo->findByCustomerRut($target); // Assuming you have a method like findByCustomerName in your repository

        $allForms = [];

        foreach ($currentCustomers as $customer) {
            $formName = sprintf("customers_%s", $customer->getId());
            $form = $factory->createNamed($formName, CustomerType::class, $customer);
            $form->handleRequest($req);

            if ($req->getMethod() === "POST" && $req->request->has($formName)) {

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($customer);
                    $entityManager->flush();
                }

                return $this->redirectToRoute('app_customer_index'); // Assuming you have a route named 'app_customer_index'
            }

            $allForms[] = [
                'form' => $form->createView(),
            ];
        }

        return new JsonResponse($this->renderView("customer/all_customer.html.twig", [
            'customers' => $currentCustomers,
            'allForms' => $allForms,
        ]));
    }

    #[Route('/broadcaster', name: 'app_search_broadcaster', methods: ['GET'])]
    public function searchBroadcaster(BroadcasterRepository $repo, Request $req, FormFactoryInterface $factory, EntityManagerInterface $entityManager): Response
    {
        // AJAX moment.
        $target = $req->get('target');
        $currentBroadcasters = $repo->findByBroadcasterRut($target); // Assuming you have a method like findByBroadcasterName in your repository
    
        $allForms = [];
    
        foreach ($currentBroadcasters as $broadcaster) {
            $formName = sprintf("broadcasters_%s", $broadcaster->getId());
            $form = $factory->createNamed($formName, BroadcasterType::class, $broadcaster);
            $form->handleRequest($req);
    
            if ($req->getMethod() === "POST" && $req->request->has($formName)) {
    
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($broadcaster);
                    $entityManager->flush();
                }
    
                return $this->redirectToRoute('app_broadcaster_index'); // Assuming you have a route named 'app_broadcaster_index'
            }
    
            $allForms[] = [
                'form' => $form->createView(),
            ];
        }
    
        return new JsonResponse($this->renderView("broadcaster/all_broadcaster.html.twig", [
            'broadcasters' => $currentBroadcasters,
            'allForms' => $allForms,
        ]));
    }

    /*#[Route('/audience', name: 'app_search_audience', methods: ['GET'])]
    public function searchAudience(AudienceRepository $repo, Request $req, FormFactoryInterface $factory, EntityManagerInterface $entityManager): Response
    {
        $target = $req->get('target');
        $currentAudiences = $repo->findByAudienceDemography($target); 

        $allForms = [];

        foreach ($currentAudiences as $audience) {
            $formName = sprintf("audiences_%s", $audience->getId());
            $form = $factory->createNamed($formName, AudienceType::class, $audience);
            $form->handleRequest($req);

            if ($req->getMethod() === "POST" && $req->request->has($formName)) {

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($audience);
                    $entityManager->flush();
                }

                return $this->redirectToRoute('app_audience_index'); 
            }

            $allForms[] = [
                'form' => $form->createView(),
            ];
        }

        return new JsonResponse($this->renderView("audience/all_audience.html.twig", [
            'audiences' => $currentAudiences,
            'allForms' => $allForms,
        ]));
    }

    #[Route('/audience', name: 'app_search_audience_by_location', methods: ['GET'])]
    public function searchAudienceByLocation(AudienceRepository $repo, Request $req, FormFactoryInterface $factory, EntityManagerInterface $entityManager): Response
    {
        $target = $req->get('target');
        $currentAudiences = $repo->findByAudienceLocation($target); 

        $allForms = [];

        foreach ($currentAudiences as $audience) {
            $formName = sprintf("audiences_%s", $audience->getId());
            $form = $factory->createNamed($formName, AudienceType::class, $audience);
            $form->handleRequest($req);

            if ($req->getMethod() === "POST" && $req->request->has($formName)) {

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($audience);
                    $entityManager->flush();
                }

                return $this->redirectToRoute('app_audience_index'); 
            }

            $allForms[] = [
                'form' => $form->createView(),
            ];
        }

        return new JsonResponse($this->renderView("audience/all_audience.html.twig", [
            'audiences' => $currentAudiences,
            'allForms' => $allForms,
        ]));
    }*/

    #[Route('/audience', name: 'app_search_audience_by_anything', methods: ['GET'])]
    public function searchAudienceByAnything(AudienceRepository $repo, Request $req, FormFactoryInterface $factory, EntityManagerInterface $entityManager): Response
    {
        $target = $req->get('target');
        $currentAudiences = $repo->findByAudienceDemography($target);//$repo->findByMultipleFields($target); 

        $allForms = [];

        foreach ($currentAudiences as $audience) {
            $formName = sprintf("audiences_%s", $audience->getId());
            $form = $factory->createNamed($formName, AudienceType::class, $audience);
            $form->handleRequest($req);

            if ($req->getMethod() === "POST" && $req->request->has($formName)) {

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($audience);
                    $entityManager->flush();
                }

                return $this->redirectToRoute('app_audience_index'); 
            }

            $allForms[] = [
                'form' => $form->createView(),
            ];
        }

        return new JsonResponse($this->renderView("audience/all_audience.html.twig", [
            'audiences' => $currentAudiences,
            'allForms' => $allForms,
        ]));
    }

}
