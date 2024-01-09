<?php

namespace App\Controller;

use App\Entity\AlertPrice;
use App\Form\AlertPriceType;
use App\Repository\AlertPriceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlertPriceController extends AbstractController
{
    #[Route('/alert/price', name: 'app_alert_price', methods: ['GET', 'POST'])]
    public function index(AlertPriceRepository $repo, Request $req, EntityManagerInterface $manager): Response
    {
        $price = $repo->findTheOnlyRow();
        $alertMessage = null;

        if($price === null) {
            $alertMessage = "No hay ningún precio para aviso, por favor se solicita colocar uno.";
            $price = new AlertPrice();
        }

        //Será el primero ya que es 'singleton'.
        $form = $this->createForm(AlertPriceType::class, $price);
        $form->handleRequest($req);

        if ($req->getMethod() === "POST") {
            if($form->isSubmitted() && $form->isValid()) {
                $manager->persist($price);
                $manager->flush();
    
                return $this->redirectToRoute('app_alert_price', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('alert_price/index.html.twig', [
            'controller_name' => 'AlertPriceController',
            'alert_message' => $alertMessage,
            'price_form' => $form
        ]);
    }
}
