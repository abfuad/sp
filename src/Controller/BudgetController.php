<?php

namespace App\Controller;

use App\Entity\Budget;
use App\Entity\BudgetExpensePlan;
use App\Entity\BudgetIncomePlan;
use App\Form\BudgetType;
use App\Repository\BudgetRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/budget')]
class BudgetController extends AbstractController
{
    use BaseControllerTrait;
    #[Route('/', name: 'app_budget_index', methods: ['GET','POST'])]
    public function index(BudgetRepository $budgetRepository,Request $request, PaginatorInterface $paginator): Response
    {
        
        // $this->denyAccessUnlessGranted('vw_ds');
            if($request->request->get('edit')){
              

                $id=$request->request->get('edit');
                $budget=$budgetRepository->findOneBy(['id'=>$id]);
                $form = $this->createForm(BudgetType::class, $budget);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid()) {
                    // $this->denyAccessUnlessGranted('edt_ds');
                   
                    $this->em->flush();
                    $this->addFlash('success', "Updated Successfuly");

        
                    return $this->redirectToRoute('app_budget_index');
                }
                $queryBuilder=$budgetRepository->filter($request->query->get('search'));
                $data=$paginator->paginate(
                    $queryBuilder,
                    $request->query->getInt('page',1),
                    18
                );
                return $this->render('budget/index.html.twig', [
                    'datas' => $data,
                    'form' => $form,
                    'edit'=>$id,
                    'entity'=>'budget'
                ]);
    
            }
            $budget = new Budget();
            $form = $this->createForm(BudgetType::class, $budget);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                //  $this->denyAccessUnlessGranted('ad_ds');

                
               $this->em->persist($budget);
               $this->em->flush();
               $this->addFlash('success', "Registered Successfuly");

    
                return $this->redirectToRoute('app_budget_index');
            }
            $queryBuilder=$budgetRepository->filter($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('budget/index.html.twig', [
                'datas' => $data,
                'form' => $form,
                'edit'=>false,
                'entity'=>'budget'
            ]);
        
       
    }
    #[Route('/{id}', name: 'app_budget_show', methods: ['GET'])]
    public function show(Budget $budget): Response
    {
        $incomePlans=$this->em->getRepository(BudgetIncomePlan::class)->findBy(['budget'=>$budget]);
        $expensePlans=$this->em->getRepository(BudgetExpensePlan::class)->findBy(['budget'=>$budget]);

        return $this->render('budget/show.html.twig', [
            'budget' => $budget,
            'income_plans'=>$incomePlans,
            'expense_plans'=> $expensePlans

        ]);
    }
   

    #[Route('/{id}', name: 'app_budget_delete', methods: ['POST'])]
    public function delete(Request $request, Budget $budget, BudgetRepository $budgetRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$budget->getId(), $request->request->get('_token'))) {
            $budgetRepository->remove($budget, true);
        }

        return $this->redirectToRoute('app_budget_index', [], Response::HTTP_SEE_OTHER);
    }
}
