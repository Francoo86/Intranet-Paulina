<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Helper;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer')]
class CustomerController extends AbstractController
{
    protected const POST_METHOD = "POST";
    protected const NEW_ELEMENT = "new_customer"; // Change "new_broadcaster" to "new_customer"
    protected const MAIN_PAGE = 'app_customer_index'; // Change "app_broadcaster_index" to "app_customer_index"
    
    #[Route('/', name: 'app_customer_index', methods: ['GET', 'POST'])] // Change "app_broadcaster_index" to "app_customer_index"
    public function index(CustomerRepository $customerRepository, Request $req, EntityManagerInterface $entityManager, FormFactoryInterface $factory): Response
    {
        $allCustomers = Helper::FindAllOrderedById($customerRepository); // Change "$broadcasterRepository" to "$customerRepository"
        $allForms = [];
    
        foreach ($allCustomers as $customer) { // Change "$broadcaster" to "$customer"
            $formName = sprintf("customer_%s", $customer->getId()); // Change "broadcaster_%s" to "customer_%s"
            $form = $factory->createNamed($formName, CustomerType::class, $customer); // Change "BroadcasterType::class" to "CustomerType::class"
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
    
    #[Route('/new', name: 'app_customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($customer);
            $entityManager->flush();

            return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_customer_show', methods: ['GET'])]
    public function show(Customer $customer): Response
    {
        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_customer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_customer_delete', methods: ['POST'])]
    public function delete(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$customer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($customer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
    }
}
