<?php

namespace App\Controller;

use App\Enhancements\ChileanInvoicePrinter;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class DTEController extends AbstractController
{
    #[Route('/boleta', name: 'app_boleta')]
    public function index(CustomerRepository $customerRepo): Response
    {
        $invoice = new ChileanInvoicePrinter();

        $customers = $customerRepo->findAll();
        $actualCustomer = new Customer();

        foreach($customers as $customer) {
            if(count($customer->getPublicity()) > 0) {
                $actualCustomer = $customer;
                break;
            }
        }
        
        /* Header settings */
        $invoice->setLogo("images/better-logo.png");   //logo image path
        $invoice->setColor("#007fff");      // pdf color scheme
        $invoice->setType("BOLETA");    // Invoice Type
        $invoice->setReference("BOL-55033645");   // Reference
        $invoice->setDate(date('M dS ,Y',time()));   //Billing Date
        $invoice->setTime(date('h:i:s A',time()));   //Billing Time
        //$invoice->setDue(date('M dS ,Y',strtotime('+3 months')));    // Due Date
        $invoice->setFrom(array("PRESTADOR DEL SERVICIO", "Radio Paulina"));//,"128 AA Juanita Ave","Glendora , CA 91740"));
        $invoice->setTo(array("COMPRADOR", $customer->getName(), $customer->getOrganisation())); //"Glendora , CA 91740"));

        $publicities = $actualCustomer->getPublicity();
        $allPubsAmount = 0;
        $allImpuestos = 0;

        foreach($publicities as $pub){
            $stock = $pub->getStock();
            $total = $stock->getAmount();
            $impuesto = $total * 0.19;

            $totalDeTotales = $total + $impuesto;

            $invoice->addItem("Publicidad", $pub->getSentence(), 1, $impuesto, $total, 0, $totalDeTotales);

            $allPubsAmount += $total;
            $allImpuestos += $impuesto;
        }
        
        $invoice->addTotal("Total", $allPubsAmount);
        $invoice->addTotal("IVA 19%", $impuesto);
        $invoice->addTotal("Monto final", $allPubsAmount + $impuesto, true);
        
        //$invoice->addBadge("Payment Paid");
        
        //$invoice->addTitle("Important Notice");
        
        //$invoice->addParagraph("No item will be replaced or refunded if you don't have the invoice with you.");
        
        $invoice->setFooternote("Radio Paulina");
        
        $invoice->render('example1.pdf','D'); 
    
        return $this->render('dte/index.html.twig', [
            'controller_name' => 'DTEController',
        ]);
    }
}
