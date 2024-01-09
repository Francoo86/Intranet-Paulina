<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Helper;
use App\Repository\CustomerRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
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

    #[Route('/{id}/publicities', name: 'app_customer_publicities', methods: ['POST'])]
    public function AllPublicities(Customer $ct, Request $req, CustomerRepository $repo){

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
