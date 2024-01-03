<?php

namespace App\Controller;

use App\Entity\Balance;
use App\Entity\Stock;
use App\Form\BalanceType;
use App\Form\StockType;
use App\Helper;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/stock')]
class StockController extends AbstractController
{
    protected const POST_METHOD = "POST";
    protected const NEW_ELEMENT = "new_stock"; // Change "new_broadcaster" to "new_stock"
    protected const MAIN_PAGE = 'app_stock_index'; // Change "app_broadcaster_index" to "app_stock_index"

    private function createForms(string $formName, string $className, array &$storage, FormFactoryInterface $factory, Request $req){

    }
    
    #[Route('/', name: 'app_stock_index', methods: ['GET', 'POST'])] // Change "app_broadcaster_index" to "app_stock_index"
    public function index(StockRepository $stockRepository, Request $req, EntityManagerInterface $entityManager, FormFactoryInterface $factory): Response
    {

        $allStocks = $stockRepository->findByAscendant(); // Change "$broadcasterRepository" to "$stockRepository"
        $allForms = [];

    
        foreach ($allStocks as $stock) { // Change "$broadcaster" to "$stock"
            $formName = sprintf("stock_%s", $stock->getId()); // Change "broadcaster_%s" to "stock_%s"
            $form = $factory->createNamed($formName, StockType::class, $stock); // Change "BroadcasterType::class" to "StockType::class"
            $form->handleRequest($req);
    
            if ($req->getMethod() === self::POST_METHOD && $req->request->has($formName)) {
    
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($stock);
                    $entityManager->flush();
                }
    
                return $this->redirectToRoute(self::MAIN_PAGE);
            }

            $actualBalance = $stock->getBalance();
            //$haveExisted = true;
            $formBalanceName =  sprintf('balance_%s_child', $stock->getId());

            if($actualBalance === null) {
                $actualBalance = new Balance();
                $formBalanceName = $formBalanceName . "_new";
            }
            
            $formBalance = $factory->createNamed($formBalanceName, BalanceType::class, $actualBalance, 
                array('stock' => $stock)
            );
            
            $formBalance->handleRequest($req);

            if ($req->getMethod() === self::POST_METHOD && $req->request->has($formBalanceName)) {

                if ($formBalance->isSubmitted() && $formBalance->isValid()) {
                    $entityManager->persist($actualBalance);
                    $entityManager->flush();
                }
    
                return $this->redirectToRoute(self::MAIN_PAGE);
            };

            $allForms[] = [
                'form' => $form->createView(),
                'balanceForm' => $formBalance->createView()
            ];
        };
    
        $newStock = new Stock(); // Change "newBroadcaster" to "newStock"
        $creationForm = $factory->createNamed(self::NEW_ELEMENT, StockType::class, $newStock); // Change "BroadcasterType::class" to "StockType::class"
        $creationForm->handleRequest($req);
    
        if ($req->getMethod() === self::POST_METHOD && $req->request->has(self::NEW_ELEMENT)) {
            if ($creationForm->isSubmitted() && $creationForm->isValid()) {
                $entityManager->persist($newStock);
                $entityManager->flush();
    
                return $this->redirectToRoute(self::MAIN_PAGE, [], Response::HTTP_SEE_OTHER);
            }
        }
    
        return $this->render('stock/index.html.twig', [
            'stocks' => $allStocks,
            'allForms' => $allForms,
            'creationForm' => $creationForm,
        ]);
    }    

    #[Route('/new', name: 'app_stock_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stock = new Stock();
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stock);
            $entityManager->flush();

            return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stock/new.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stock_show', methods: ['GET'])]
    public function show(Stock $stock): Response
    {
        return $this->render('stock/show.html.twig', [
            'stock' => $stock,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stock_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stock $stock, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stock/edit.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stock_delete', methods: ['POST'])]
    public function delete(Request $request, Stock $stock, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stock->getId(), $request->request->get('_token'))) {
            $entityManager->remove($stock);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
    }
}
