<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Helper;
use App\Repository\CustomerRepository;
use DateTimeImmutable;
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
    
    #[Route('/', name: 'app_customer_index', methods: ['GET', 'POST'])]
    public function index(CustomerRepository $customerRepository, Request $req, EntityManagerInterface $entityManager, FormFactoryInterface $factory): Response
    {
        $allCustomers = Helper::FindAllOrderedById($customerRepository);
        $allForms = [];
    
        foreach ($allCustomers as $customer) {
            $formName = sprintf("customer_%s", $customer->getId());
            $form = $factory->createNamed($formName, CustomerType::class, $customer);
            $form->handleRequest($req);
    
            if ($req->getMethod() === self::POST_METHOD && $req->request->has($formName)) {
    
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($customer);
                    $entityManager->flush();
                }
    
                return $this->redirectToRoute(self::MAIN_PAGE);
            }
    
            $allForms[] = [
                'form' => $form->createView(),
            ];
        }
    
        $newCustomer = new Customer(); // Change "newBroadcaster" to "newCustomer"
        $creationForm = $factory->createNamed(self::NEW_ELEMENT, CustomerType::class, $newCustomer); // Change "BroadcasterType::class" to "CustomerType::class"
        $creationForm->handleRequest($req);
    
        if ($req->getMethod() === self::POST_METHOD && $req->request->has(self::NEW_ELEMENT)) {
            if ($creationForm->isSubmitted() && $creationForm->isValid()) {
                $entityManager->persist($newCustomer);
                $entityManager->flush();
    
                return $this->redirectToRoute(self::MAIN_PAGE, [], Response::HTTP_SEE_OTHER);
            }
        }
    
        return $this->render('customer/customer_index.html.twig', [ // Change "'broadcaster/broadcaster_index.html.twig'" to "'customer/customer_index.html.twig'"
            'customers' => $allCustomers, // Change "'broadcasters'" to "'customers'"
            'allForms' => $allForms,
            'creationForm' => $creationForm
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
