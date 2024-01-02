<?php

namespace App\Controller;

use App\Entity\Publicity;
use App\Form\PublicityType;
use App\Helper;
use App\Repository\PublicityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/publicity')]
class PublicityController extends AbstractController
{
    protected const POST_METHOD = "POST";
    protected const NEW_ELEMENT = "new_show";
    protected const MAIN_PAGE = 'app_publicity_index';

    #[Route('/', name: 'app_publicity_index', methods: ['GET'])]
    public function index(PublicityRepository $publicityRepository, FormFactoryInterface $factory, Request $req, EntityManagerInterface $entityManager): Response
    {
        $allPublicities = Helper::FindAllOrderedById($publicityRepository);//$publicityRepository->findAllOrderedById();
        $allForms = [];

        foreach ($allPublicities as $publicity) {
            $formName = sprintf("publicity_%s", $publicity->getId());
            $form = $factory->createNamed($formName, PublicityType::class, $publicity);
            $form->handleRequest($req);

            if ($req->getMethod() === self::POST_METHOD && $req->request->has($formName)) {

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($publicity);
                    $entityManager->flush();
                }

                return $this->redirectToRoute(self::MAIN_PAGE);
            }

            $allForms[] = [
                'form' => $form->createView(),
            ];
        }

        $newGuideline = new Publicity();
        $creationForm = $factory->createNamed(self::NEW_ELEMENT, PublicityType::class, $newGuideline);
        $creationForm->handleRequest($req);

        if($req->getMethod() === self::POST_METHOD && $req->request->has(self::NEW_ELEMENT)){
            if ($creationForm->isSubmitted() && $creationForm->isValid()) {
                $entityManager->persist($newGuideline);
                $entityManager->flush();
    
                return $this->redirectToRoute(self::MAIN_PAGE, [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('publicity/index.html.twig', [
            'publicities' => $allPublicities,
            'allForms' => $allForms,
            'creationForm' => $creationForm
        ]);
        /*
        return $this->render('publicity/index.html.twig', [
            'publicities' => $publicityRepository->findAll(),
        ]);*/
    }

    #[Route('/new', name: 'app_publicity_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publicity = new Publicity();
        $form = $this->createForm(PublicityType::class, $publicity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($publicity);
            $entityManager->flush();

            return $this->redirectToRoute('app_publicity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publicity/new.html.twig', [
            'publicity' => $publicity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publicity_show', methods: ['GET'])]
    public function show(Publicity $publicity): Response
    {
        return $this->render('publicity/show.html.twig', [
            'publicity' => $publicity,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_publicity_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publicity $publicity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublicityType::class, $publicity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_publicity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publicity/edit.html.twig', [
            'publicity' => $publicity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publicity_delete', methods: ['POST'])]
    public function delete(Request $request, Publicity $publicity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publicity->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publicity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publicity_index', [], Response::HTTP_SEE_OTHER);
    }
}
