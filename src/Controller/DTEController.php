<?php

namespace App\Controller;

use App\Enhancements\ChileanInvoicePrinter;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Konekt\PdfInvoice\InvoicePrinter;

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
        $invoice->setType("Sale Invoice");    // Invoice Type
        $invoice->setReference("BOL-55033645");   // Reference
        $invoice->setDate(date('M dS ,Y',time()));   //Billing Date
        $invoice->setTime(date('h:i:s A',time()));   //Billing Time
        //$invoice->setDue(date('M dS ,Y',strtotime('+3 months')));    // Due Date
        $invoice->setFrom(array("PRESTADOR DEL SERVICIO", "Radio Paulina"));//,"128 AA Juanita Ave","Glendora , CA 91740"));
        $invoice->setTo(array("COMPRADOR", $customer->getName(), $customer->getOrganisation())); //"Glendora , CA 91740"));

        $publicities = $actualCustomer->getPublicity();

        foreach($publicities as $pub){
            //$invoice->addItem($pub->getStock()->getAmount());
        }
        
        
        $invoice->addItem("AMD Athlon X2DC-7450","2.4GHz/1GB/160GB/SMP-DVD/VB",6,0,580,0,3480);
        $invoice->addItem("PDC-E5300","2.6GHz/1GB/320GB/SMP-DVD/FDD/VB",4,0,645,0,2580);
        $invoice->addItem('LG 18.5" WLCD',"",10,0,230,0,2300);
        $invoice->addItem("HP LaserJet 5200","",1,0,1100,0,1100);
        
        $invoice->addTotal("Total",9460);
        $invoice->addTotal("VAT 21%",1986.6);
        $invoice->addTotal("Total due",11446.6,true);
        
        $invoice->addBadge("Payment Paid");
        
        $invoice->addTitle("Important Notice");
        
        $invoice->addParagraph("No item will be replaced or refunded if you don't have the invoice with you.");
        
        $invoice->setFooternote("My Company Name Here");
        
        $invoice->render('example1.pdf','D'); 
    
        return $this->render('dte/index.html.twig', [
            'controller_name' => 'DTEController',
        ]);
    }
}
