<?php

namespace App\Controller;

use App\Entity\BudgetIncomePlan;
use App\Form\BudgetIncomePlanType;
use App\Repository\BudgetIncomePlanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/income-plan')]
class BudgetIncomePlanController extends AbstractController
{
    #[Route('/', name: 'app_budget_income_plan_index', methods: ['GET'])]
    public function index(BudgetIncomePlanRepository $budgetIncomePlanRepository): Response
    {
        return $this->render('budget_income_plan/index.html.twig', [
            'budget_income_plans' => $budgetIncomePlanRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_budget_income_plan_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BudgetIncomePlanRepository $budgetIncomePlanRepository): Response
    {
        $budgetIncomePlan = new BudgetIncomePlan();
        $form = $this->createForm(BudgetIncomePlanType::class, $budgetIncomePlan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $budgetIncomePlanRepository->save($budgetIncomePlan, true);

            return $this->redirectToRoute('app_budget_income_plan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('budget_income_plan/new.html.twig', [
            'budget_income_plan' => $budgetIncomePlan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_budget_income_plan_show', methods: ['GET'])]
    public function show(BudgetIncomePlan $budgetIncomePlan): Response
    {
        return $this->render('budget_income_plan/show.html.twig', [
            'budget_income_plan' => $budgetIncomePlan,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_budget_income_plan_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BudgetIncomePlan $budgetIncomePlan, BudgetIncomePlanRepository $budgetIncomePlanRepository): Response
    {
        $form = $this->createForm(BudgetIncomePlanType::class, $budgetIncomePlan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $budgetIncomePlanRepository->save($budgetIncomePlan, true);

            return $this->redirectToRoute('app_budget_income_plan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('budget_income_plan/edit.html.twig', [
            'budget_income_plan' => $budgetIncomePlan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_budget_income_plan_delete', methods: ['POST'])]
    public function delete(Request $request, BudgetIncomePlan $budgetIncomePlan, BudgetIncomePlanRepository $budgetIncomePlanRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$budgetIncomePlan->getId(), $request->request->get('_token'))) {
            $budgetIncomePlanRepository->remove($budgetIncomePlan, true);
        }

        return $this->redirectToRoute('app_budget_income_plan_index', [], Response::HTTP_SEE_OTHER);
    }
}
