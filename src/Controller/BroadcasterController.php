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
    protected const NEW_ELEMENT = "new_broadcaster";
    protected const MAIN_PAGE = 'app_broadcaster_index';

    public function createBroadcasterForm(
        Broadcaster $broadcaster = null, 
        string $formName, 
        Request $req, 
        FormFactoryInterface $factory,
        )
    {
        $form = $factory->createNamed($formName, BroadcasterType::class, $broadcaster);
        $form->handleRequest($req);
    
        return $form;
    }

    #[Route('/', name: 'app_broadcaster_index', methods: ['GET', 'POST'])]
    public function index(BroadcasterRepository $broadcasterRepository, Request $req, EntityManagerInterface $entityManager, FormFactoryInterface $factory): Response
    {
        $allBroadcasters = Helper::FindAllOrderedById($broadcasterRepository);
        $allForms = [];

        foreach ($allBroadcasters as $broadcaster) {
            $formName = sprintf("broadcaster_%s", $broadcaster->getId());
            $form = $this->createBroadcasterForm($broadcaster, $formName, $req, $factory);

            if(Helper::IsValidForm($req, $form, $formName)) {
                $res = Helper::SendPersonToDB($entityManager, $broadcaster);
                if(!$res) {
                    $this->addFlash("error", "El RUT no es válido, tiene que poseer 7 u 8 caracteres.");
                }

                return $this->redirectToRoute(self::MAIN_PAGE);
            }

            $allForms[] = [
                'form' => $form->createView(),
            ];
        }

        $newBroadcaster = new Broadcaster();
        $creationForm = $this->createBroadcasterForm($newBroadcaster, self::NEW_ELEMENT, $req, $factory);

        if(Helper::IsValidForm($req, $creationForm, self::NEW_ELEMENT)){
            $res = Helper::SendPersonToDB($entityManager, $newBroadcaster);
            if(!$res) {
                $this->addFlash("error", "El RUT no es válido, tiene que poseer 7 u 8 caracteres.");
            }
            return $this->redirectToRoute(self::MAIN_PAGE, [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('broadcaster/broadcaster_index.html.twig', [
            'broadcasters' => $allBroadcasters,
            'allForms' => $allForms,
            'creationForm' => $creationForm
        ]);
    }

    #[Route('/{id}', name: 'app_broadcaster_show', methods: ['GET'])]
    public function show(Broadcaster $broadcaster): Response
    {
        return $this->render('broadcaster/show.html.twig', [
            'broadcaster' => $broadcaster,
        ]);
    }

    #[Route('/{id}', name: 'app_broadcaster_delete', methods: ['POST'])]
    public function delete(Request $request, Broadcaster $broadcaster, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$broadcaster->getId(), $request->request->get('_token'))) {
            $broadcaster->setDeletedAt(new DateTimeImmutable());
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_broadcaster_index', [], Response::HTTP_SEE_OTHER);
    }
}
