<?php

namespace App\Controller;

use App\Entity\Balance;
use App\Form\BalanceType;
use App\Repository\BalanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/balance')]
class BalanceController extends AbstractController
{
    #[Route('/', name: 'app_balance_index', methods: ['GET'])]
    public function index(BalanceRepository $balanceRepository): Response
    {
        return $this->render('balance/index.html.twig', [
            'balances' => $balanceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_balance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $balance = new Balance();
        $form = $this->createForm(BalanceType::class, $balance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($balance);
            $entityManager->flush();

            return $this->redirectToRoute('app_balance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('balance/new.html.twig', [
            'balance' => $balance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_balance_show', methods: ['GET'])]
    public function show(Balance $balance): Response
    {
        return $this->render('balance/show.html.twig', [
            'balance' => $balance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_balance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Balance $balance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BalanceType::class, $balance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_balance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('balance/edit.html.twig', [
            'balance' => $balance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_balance_delete', methods: ['POST'])]
    public function delete(Request $request, Balance $balance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$balance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($balance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_balance_index', [], Response::HTTP_SEE_OTHER);
    }
}
