<?php

namespace App\Controller;

use App\Entity\Publicity;
use App\Form\PublicityType;
use App\Helper;
use App\Repository\PublicityRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/', name: 'app_publicity_index', methods: ['GET', 'POST'])]
    public function index(PublicityRepository $publicityRepository, FormFactoryInterface $factory, Request $req, EntityManagerInterface $entityManager): Response
    {
        $allPublicities = Helper::FindAllOrderedById($publicityRepository);
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
    }

    #[Route('/{id}', name: 'app_publicity_delete', methods: ['POST'])]
    public function delete(Request $request, Publicity $publicity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publicity->getId(), $request->request->get('_token'))) {
            $publicity->setDeletedAt(new DateTimeImmutable());
            //$entityManager->remove($publicity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publicity_index', [], Response::HTTP_SEE_OTHER);
    }
}
