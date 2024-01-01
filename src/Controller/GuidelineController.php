<?php

namespace App\Controller;

use App\Entity\Guideline;
use App\Form\GuidelineType;
use App\Helper;
use App\Repository\GuidelineRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/guideline')]
class GuidelineController extends AbstractController
{
    protected const POST_METHOD = "POST";
    protected const NEW_ELEMENT = "new_guideline";
    protected const MAIN_PAGE = 'app_guideline_index';

    #[Route('/', name: 'app_guideline_index', methods: ['GET', 'POST'])]
    public function index(GuidelineRepository $guidelineRepository, Request $req, EntityManagerInterface $entityManager, FormFactoryInterface $factory): Response
    {
        $allGuidelines = Helper::FindAllOrderedById($guidelineRepository);//$guidelineRepository->findAllOrderedById();
        $allForms = [];

        foreach ($allGuidelines as $guideline) {
            $formName = sprintf("guideline_%s", $guideline->getId());
            $form = $factory->createNamed($formName, GuidelineType::class, $guideline);
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

        $newGuideline = new Guideline();
        $creationForm = $factory->createNamed(self::NEW_ELEMENT, GuidelineType::class, $newGuideline);
        $creationForm->handleRequest($req);

        if($req->getMethod() === self::POST_METHOD && $req->request->has(self::NEW_ELEMENT)){
            if ($creationForm->isSubmitted() && $creationForm->isValid()) {
                $entityManager->persist($newGuideline);
                $entityManager->flush();
    
                return $this->redirectToRoute(self::MAIN_PAGE, [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('guideline/crud_guideline.html.twig', [
            'guidelines' => $allGuidelines,
            'allForms' => $allForms,
            'creationForm' => $creationForm
        ]);
    }

    #[Route('/{id}', name: 'app_guideline_show', methods: ['GET'])]
    public function show(Guideline $guideline, SerializerInterface $serial): Response
    {
        $jsonObject = $serial->serialize($guideline, 'json', [    
            'circular_reference_handler' => function ($object) {
            return $object->getId();
        }]);

        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}/delete', name: 'app_guideline_delete', methods: ['POST'])]
    public function delete(Request $request, Guideline $guideline, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$guideline->getId(), $request->request->get('_token'))) {
            $guideline->setDeletedAt(new DateTimeImmutable());
            $entityManager->persist($guideline);
            //$entityManager->remove($guideline);
            $entityManager->flush();
        }

        return $this->redirectToRoute(self::MAIN_PAGE, [], Response::HTTP_SEE_OTHER);
    }
}
