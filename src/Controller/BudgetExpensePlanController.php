<?php

namespace App\Controller;

use App\Entity\BudgetExpensePlan;
use App\Entity\BudgetIncomePlan;
use App\Entity\Credit;
use App\Entity\Expense;
use App\Entity\ExpenseType;
use App\Form\BudgetExpensePlanType;
use App\Form\CreditType;
use App\Form\ExpenseType as FormExpenseType;
use App\Repository\BudgetExpensePlanRepository;
use App\Repository\CreditRepository;
use App\Repository\ExpenseRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expense-plan')]
class BudgetExpensePlanController extends AbstractController
{
    
    use BaseControllerTrait;
    #[Route('/', name: 'app_budget_expense_plan_index', methods: ['GET','POST'])]
    public function index(BudgetExpensePlanRepository $budgetExpensePlanRepository,Request $request, PaginatorInterface $paginator): Response
    {
        
        // $this->denyAccessUnlessGranted('vw_ds');
            if($request->request->get('edit')){
              

                $id=$request->request->get('edit');
                $budgetExpensePlan=$budgetExpensePlanRepository->findOneBy(['id'=>$id]);
                $form = $this->createForm(BudgetExpensePlanType::class, $budgetExpensePlan);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid()) {
                    // $this->denyAccessUnlessGranted('edt_ds');
                   
                    $this->em->flush();
                    $this->addFlash('success', "Updated Successfuly");

        
                    return $this->redirectToRoute('app_budget_expense_plan_index');
                }
                $queryBuilder=$budgetExpensePlanRepository->filter($request->query->get('search'));
                $data=$paginator->paginate(
                    $queryBuilder,
                    $request->query->getInt('page',1),
                    18
                );
                return $this->render('budget_expense_plan/index.html.twig', [
                    'datas' => $data,
                    'form' => $form,
                    'edit'=>$id,
                    'entity'=>'budget_expense_plan'
                ]);
    
            }
            $budgetExpensePlan = new BudgetExpensePlan();
            $form = $this->createForm(BudgetExpensePlanType::class, $budgetExpensePlan);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                //  $this->denyAccessUnlessGranted('ad_ds');

                
               $this->em->persist($budgetExpensePlan);
               $this->em->flush();
               $this->addFlash('success', "Registered Successfuly");

    
                return $this->redirectToRoute('app_budget_expense_plan_index');
            }
            $queryBuilder=$budgetExpensePlanRepository->filter($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('budget_expense_plan/index.html.twig', [
                'datas' => $data,
                'form' => $form,
                'edit'=>false,
                'entity'=>'budget_expense_plan'
            ]);
        
       
    }
    #[Route('/new', name: 'app_budget_expense_plan_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BudgetExpensePlanRepository $budgetExpensePlanRepository): Response
    {
        $budgetExpensePlan = new BudgetExpensePlan();
        $form = $this->createForm(BudgetExpensePlanType::class, $budgetExpensePlan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $budgetExpensePlanRepository->save($budgetExpensePlan, true);

            return $this->redirectToRoute('app_budget_expense_plan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('budget_expense_plan/new.html.twig', [
            'budget_expense_plan' => $budgetExpensePlan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_budget_expense_plan_show', methods: ['GET','POST'])]
    public function show(CreditRepository $creditRepository,ExpenseRepository $expenseRepository,BudgetExpensePlan $budgetExpensePlan,Request $request): Response
    {
        if($request->request->get('credit_remove')){
            $id=$request->request->get('credit_remove');
            $credit=$creditRepository->findOneBy(['id'=>$id]);
            $creditRepository->remove($credit,true);
            $this->addFlash('success', "Deleted Successfuly");

    
            return $this->redirectToRoute('app_budget_expense_plan_show',['id'=>$budgetExpensePlan->getId()]);
        }
        if($request->request->get('credit_edit')){
              

            $id=$request->request->get('credit_edit');
            $credit=$creditRepository->findOneBy(['id'=>$id]);
            // $total=$expenseRepository->expenseReport(['expensePlan'=>$budgetExpensePlan]);
            // $remain=$budgetExpensePlan->getPlanValue()-$total;

            $creditForm = $this->createForm(CreditType::class, $credit);
        $creditForm->handleRequest($request);

            if ($creditForm->isSubmitted() && $creditForm->isValid()) {
               
                $this->em->flush();
                $this->addFlash('success', "Updated Successfuly");

    
                return $this->redirectToRoute('app_budget_expense_plan_show',['id'=>$budgetExpensePlan->getId()]);
            }
            $total=$expenseRepository->expenseReport(['expensePlan'=>$budgetExpensePlan]);
            $remain=$budgetExpensePlan->getPlanValue()-$total;

           
            return $this->render('budget_expense_plan/show.html.twig', [
                'budget_expense_plan' => $budgetExpensePlan,
                'credit_form' => $creditForm,
                'credit_edit'=>$id,
                'remain'=>$remain,
                'edit'=>false,

            ]);

        }
        $credit = new Credit();
        $creditForm = $this->createForm(CreditType::class, $credit);
        $creditForm->handleRequest($request);

        if ($creditForm->isSubmitted() && $creditForm->isValid()) {
           $credit->setExpensePlan($budgetExpensePlan);
           $credit->setStatus(0);
           $this->em->persist($credit);
           $this->em->flush();
           $this->addFlash('success', "Registered Successfuly");
            return $this->redirectToRoute('app_budget_expense_plan_show',['id'=>$budgetExpensePlan->getId()]);
        }

        if($request->request->get('remove')){
            $id=$request->request->get('remove');
            $expense=$expenseRepository->findOneBy(['id'=>$id]);
            $expenseRepository->remove($expense,true);
            $this->addFlash('success', "Deleted Successfuly");

    
            return $this->redirectToRoute('app_budget_expense_plan_show',['id'=>$budgetExpensePlan->getId()]);
        }
        if($request->request->get('edit')){
              

            $id=$request->request->get('edit');
            $expense=$expenseRepository->findOneBy(['id'=>$id]);
            $total=$expenseRepository->expenseReport(['expensePlan'=>$budgetExpensePlan]);
            $remain=$budgetExpensePlan->getPlanValue()-$total;

            $form = $this->createForm(FormExpenseType::class, $expense);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if($remain<$expense->getAmount()){
                    $this->addFlash('danger', " Low Balance");
    
                    return $this->redirectToRoute('app_budget_expense_plan_show',['id'=>$budgetExpensePlan->getId()]);
                   }
                $this->em->flush();
                $this->addFlash('success', "Updated Successfuly");

    
                return $this->redirectToRoute('app_budget_expense_plan_show',['id'=>$budgetExpensePlan->getId()]);
            }
            $total=$expenseRepository->expenseReport(['expensePlan'=>$budgetExpensePlan]);
            $remain=$budgetExpensePlan->getPlanValue()-$total;

           
            return $this->render('budget_expense_plan/show.html.twig', [
                'budget_expense_plan' => $budgetExpensePlan,
                'form' => $form,
                'edit'=>$id,
                'remain'=>$remain,
                'credit_edit'=>false,


            ]);

        }
        $total= $expenseRepository->expenseReport(['expensePlan'=>$budgetExpensePlan]);
        $remain=$budgetExpensePlan->getPlanValue()-$total;

            $expense = new Expense();
            $form = $this->createForm(FormExpenseType::class, $expense);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                   if($remain<$expense->getAmount()){
                    $this->addFlash('danger', " Low Balance");
    
                    return $this->redirectToRoute('app_budget_expense_plan_show',['id'=>$budgetExpensePlan->getId()]);
                   }

               $expense->setExpensePlan($budgetExpensePlan);
               $this->em->persist($expense);
               $this->em->flush();
               $this->addFlash('success', "Registered Successfuly");
    
                return $this->redirectToRoute('app_budget_expense_plan_show',['id'=>$budgetExpensePlan->getId()]);
            }
        return $this->render('budget_expense_plan/show.html.twig', [
            'budget_expense_plan' => $budgetExpensePlan,
            'form'=>$form,
            'edit'=>false,
            'remain'=>$remain,
            'credit_form' => $creditForm,
            'credit_edit'=>false,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_budget_expense_plan_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BudgetExpensePlan $budgetExpensePlan, BudgetExpensePlanRepository $budgetExpensePlanRepository): Response
    {
        $form = $this->createForm(BudgetExpensePlanType::class, $budgetExpensePlan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $budgetExpensePlanRepository->save($budgetExpensePlan, true);

            return $this->redirectToRoute('app_budget_expense_plan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('budget_expense_plan/edit.html.twig', [
            'budget_expense_plan' => $budgetExpensePlan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_budget_expense_plan_delete', methods: ['POST'])]
    public function delete(Request $request, BudgetExpensePlan $budgetExpensePlan, BudgetExpensePlanRepository $budgetExpensePlanRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$budgetExpensePlan->getId(), $request->request->get('_token'))) {
            $budgetExpensePlanRepository->remove($budgetExpensePlan, true);
        }

        return $this->redirectToRoute('app_budget_expense_plan_index', [], Response::HTTP_SEE_OTHER);
    }
}
