<?php

namespace App\Controller;

use App\Enhancements\ChileanInvoicePrinter;
use App\Entity\Customer;
use App\Form\CustomerType;
use App\Helper;
use App\Repository\CustomerRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer')]
class CustomerController extends AbstractController
{
    //protected const POST_METHOD = "POST";
    protected const NEW_ELEMENT = "new_customer";
    protected const MAIN_PAGE = 'app_customer_index';

    public function createCustomerForm(
        Customer $customer = null, 
        string $formName, 
        Request $req, 
        FormFactoryInterface $factory,
        )
    {
        $form = $factory->createNamed($formName, CustomerType::class, $customer);
        $form->handleRequest($req);
    
        return $form;
    }
    
    #[Route('/', name: 'app_customer_index', methods: ['GET', 'POST'])]
    public function index(CustomerRepository $customerRepository, Request $req, EntityManagerInterface $entityManager, FormFactoryInterface $factory): Response
    {
        $allCustomers = Helper::FindAllOrderedById($customerRepository);
        $allForms = [];
    
        foreach ($allCustomers as $customer) {
            $formName = sprintf("customer_%s", $customer->getId());
            $form = $this->createCustomerForm($customer, $formName, $req, $factory);//$factory->createNamed($formName, CustomerType::class, $customer);

            if(Helper::IsValidForm($req, $form, $formName)) {
                Helper::SendPersonToDB($entityManager, $customer);
                return $this->redirectToRoute(self::MAIN_PAGE);
            }
    
            $allForms[] = [
                'form' => $form->createView(),
            ];
        }
    
        $newCustomer = new Customer();
        $creationForm = $this->createCustomerForm($newCustomer, self::NEW_ELEMENT, $req, $factory);

        if(Helper::IsValidForm($req, $creationForm, self::NEW_ELEMENT)){
            Helper::SendPersonToDB($entityManager, $newCustomer);
            return $this->redirectToRoute(self::MAIN_PAGE, [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('customer/customer_index.html.twig', [
            'customers' => $allCustomers,
            'allForms' => $allForms,
            'creationForm' => $creationForm
        ]);
    }

    #[Route('/{id}/boleta', name: 'app_customer_boletas', methods: ['GET'])]
    public function Boleta(Customer $ct, Request $req) : Response {
        $publicities = $ct->getPublicity();

        if(count($publicities) <= 0){
            return new JsonResponse([
                'message' => 'Este cliente no posee publicidades como para crear una boleta.'
            ]);
        }

        $invoice = new ChileanInvoicePrinter();
            
        /* Header settings */
        $invoice->setLogo("images/better-logo.png");   //logo image path
        $invoice->setColor("#007fff");      // pdf color scheme
        $invoice->setType("BOLETA");    // Invoice Type
        $invoice->setReference("BOL-55033645");   // Reference
        $invoice->setDate(date('M dS ,Y',time()));   //Billing Date
        $invoice->setTime(date('h:i:s A',time()));   //Billing Time
        //$invoice->setDue(date('M dS ,Y',strtotime('+3 months')));    // Due Date
        $invoice->setFrom(array("PRESTADOR DEL SERVICIO", "Radio Paulina"));//,"128 AA Juanita Ave","Glendora , CA 91740"));
        $invoice->setTo(array("COMPRADOR", $ct->getName(), $ct->getOrganisation())); //"Glendora , CA 91740"));

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
        
        $invoice->setFooternote("Radio Paulina");
        
        $fileName = sprintf("%s_boleta_ref.pdf", $ct->getRut());
        $invoice->render($fileName, 'D');

        return new JsonResponse([
            'message' => 'Downloaded with success',
        ]);
    }

    #[Route('/{id}', name: 'app_customer_delete', methods: ['POST'])]
    public function delete(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$customer->getId(), $request->request->get('_token'))) {
            $customer->setDeletedAt(new DateTimeImmutable());
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
    }
}
