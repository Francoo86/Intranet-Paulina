<?php

namespace App\Controller;

use App\Enhancements\ChileanInvoicePrinter;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class DTEController extends AbstractController
{
    #[Route('/boleta', name: 'app_boleta')]
    public function index(CustomerRepository $customerRepo): Response
    {
        return $this->render('dte/index.html.twig', [
            'controller_name' => 'DTEController',
        ]);
    }


    #[Route('/boleta/search/', name: 'app_search_boletas', methods: ['GET'])]
    public function generarBoleta(CustomerRepository $customerRepo, Request $req) : Response {
        $rut = $req->get("rut_boleta");

        $allCustomers = $customerRepo->findByCustomerRut($rut);
        $withBoleta = [];

        foreach($allCustomers as $customer) {
            $counter = $customer->getPublicity();
            if(count($counter) <= 0){
                continue;
            }

            $data = [
                "rut" => sprintf("%s_%s", $customer->getRut(), $customer->getDv()),
                "nombre" => $customer->getName(),
                "empresa" => $customer->getOrganisation(),
                "pubcount" => count($counter),
                "link" =>  $this->generateUrl('app_customer_boletas', ['id' => $customer->getId()]),
            ];

            $withBoleta[] = $data;
        };

        return new JsonResponse($withBoleta);
    }
}
