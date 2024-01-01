<?php

namespace App\Controller;

use App\Entity\Show;
use App\Form\ShowType;
use App\Helper;
use App\Repository\ShowRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/show')]
class ShowController extends AbstractController
{
    protected const POST_METHOD = "POST";
    protected const NEW_ELEMENT = "new_show";
    protected const MAIN_PAGE = 'app_show_index';

    #[Route('/', name: 'app_show_index', methods: ['GET', 'POST'])]
    public function index(ShowRepository $guidelineRepository, Request $req, EntityManagerInterface $entityManager, FormFactoryInterface $factory): Response
    {
        $allGuidelines = Helper::FindAllOrderedById($guidelineRepository);//$guidelineRepository->findAll();
        $allForms = [];

        foreach ($allGuidelines as $guideline) {
            $formName = sprintf("guideline_%s", $guideline->getId());
            $form = $factory->createNamed($formName, ShowType::class, $guideline);
            $form->handleRequest($req);

            if ($req->getMethod() === self::POST_METHOD && $req->request->has($formName)) {

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($guideline);
                    $entityManager->flush();
                }

                return $this->redirectToRoute(self::MAIN_PAGE);
            }

            $allForms[] = [
                'form' => $form->createView(),
            ];
        }

        $newGuideline = new Show();
        $creationForm = $factory->createNamed(self::NEW_ELEMENT, ShowType::class, $newGuideline);
        $creationForm->handleRequest($req);

        if($req->getMethod() === self::POST_METHOD && $req->request->has(self::NEW_ELEMENT)){
            if ($creationForm->isSubmitted() && $creationForm->isValid()) {
                $entityManager->persist($newGuideline);
                $entityManager->flush();
    
                return $this->redirectToRoute(self::MAIN_PAGE, [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('show/index.html.twig', [
            'shows' => $allGuidelines,
            'allForms' => $allForms,
            'creationForm' => $creationForm
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
            $show->setDeletedAt(new DateTimeImmutable());
            //$entityManager->remove($show);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_show_index', [], Response::HTTP_SEE_OTHER);
    }
}
