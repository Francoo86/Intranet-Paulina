<?php

namespace App\Controller;

use App\Entity\Broadcaster;
use App\Form\BroadcasterType;
use App\Helper;
use App\Repository\BroadcasterRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/broadcaster')]
class BroadcasterController extends AbstractController
{
    protected const POST_METHOD = "POST";
    protected const NEW_ELEMENT = "new_guideline";
    protected const MAIN_PAGE = 'app_broadcaster_index';

    #[Route('/', name: 'app_broadcaster_index', methods: ['GET', 'POST'])]
    public function index(BroadcasterRepository $broadcasterRepository, Request $req, EntityManagerInterface $entityManager, FormFactoryInterface $factory): Response
    {
        $allBroadcasters = Helper::FindAllOrderedById($broadcasterRepository);
        $allForms = [];

        foreach ($allBroadcasters as $broadcaster) {
            $formName = sprintf("broadcaster_%s", $broadcaster->getId());
            $form = $factory->createNamed($formName, BroadcasterType::class, $broadcaster);
            $form->handleRequest($req);

            if ($req->getMethod() === self::POST_METHOD && $req->request->has($formName)) {

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($broadcaster);
                    $entityManager->flush();
                }

                return $this->redirectToRoute(self::MAIN_PAGE);
            }

            $allForms[] = [
                'form' => $form->createView(),
            ];
        }

        $newBroadcaster = new Broadcaster();
        $creationForm = $factory->createNamed(self::NEW_ELEMENT, BroadcasterType::class, $newBroadcaster);
        $creationForm->handleRequest($req);

        if($req->getMethod() === self::POST_METHOD && $req->request->has(self::NEW_ELEMENT)){
            if ($creationForm->isSubmitted() && $creationForm->isValid()) {
                $entityManager->persist($newBroadcaster);
                $entityManager->flush();
    
                return $this->redirectToRoute(self::MAIN_PAGE, [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('broadcaster/broadcaster_index.html.twig', [
            'broadcasters' => $allBroadcasters,
            'allForms' => $allForms,
            'creationForm' => $creationForm
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
            $broadcaster->setDeletedAt(new DateTimeImmutable());
            //$entityManager->remove($broadcaster);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_broadcaster_index', [], Response::HTTP_SEE_OTHER);
    }
}
