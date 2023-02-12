<?php

namespace App\Controller;

use App\Entity\ExpenseType;
use App\Form\ExpenseTypeType;
use App\Repository\ExpenseTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expense/type')]
class ExpenseTypeController extends AbstractController
{
    #[Route('/', name: 'app_expense_type_index', methods: ['GET'])]
    public function index(ExpenseTypeRepository $expenseTypeRepository): Response
    {
        return $this->render('expense_type/index.html.twig', [
            'expense_types' => $expenseTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_expense_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ExpenseTypeRepository $expenseTypeRepository): Response
    {
        $expenseType = new ExpenseType();
        $form = $this->createForm(ExpenseTypeType::class, $expenseType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $expenseTypeRepository->save($expenseType, true);

            return $this->redirectToRoute('app_expense_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('expense_type/new.html.twig', [
            'expense_type' => $expenseType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_expense_type_show', methods: ['GET'])]
    public function show(ExpenseType $expenseType): Response
    {
        return $this->render('expense_type/show.html.twig', [
            'expense_type' => $expenseType,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_expense_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ExpenseType $expenseType, ExpenseTypeRepository $expenseTypeRepository): Response
    {
        $form = $this->createForm(ExpenseTypeType::class, $expenseType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $expenseTypeRepository->save($expenseType, true);

            return $this->redirectToRoute('app_expense_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('expense_type/edit.html.twig', [
            'expense_type' => $expenseType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_expense_type_delete', methods: ['POST'])]
    public function delete(Request $request, ExpenseType $expenseType, ExpenseTypeRepository $expenseTypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$expenseType->getId(), $request->request->get('_token'))) {
            $expenseTypeRepository->remove($expenseType, true);
        }

        return $this->redirectToRoute('app_expense_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
