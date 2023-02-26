<?php

namespace App\Controller;

use App\Entity\BudgetIncomePlan;
use App\Form\BudgetIncomePlanType;
use App\Repository\BudgetIncomePlanRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/income-plan')]
class BudgetIncomePlanController extends AbstractController
{
   
    use BaseControllerTrait;
    #[Route('/', name: 'app_budget_income_plan_index', methods: ['GET','POST'])]
    public function index(BudgetIncomePlanRepository $budgetIncomePlanRepository,Request $request, PaginatorInterface $paginator): Response
    {
        
        // $this->denyAccessUnlessGranted('vw_ds');
            if($request->request->get('edit')){
              

                $id=$request->request->get('edit');
                $budgetIncomePlan=$budgetIncomePlanRepository->findOneBy(['id'=>$id]);
                $form = $this->createForm(BudgetIncomePlanType::class, $budgetIncomePlan);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid()) {
                    // $this->denyAccessUnlessGranted('edt_ds');
                   
                    $this->em->flush();
                    $this->addFlash('success', "Updated Successfuly");

        
                    return $this->redirectToRoute('app_budget_income_plan_index');
                }
                $queryBuilder=$budgetIncomePlanRepository->filter($request->query->get('search'));
                $data=$paginator->paginate(
                    $queryBuilder,
                    $request->query->getInt('page',1),
                    18
                );
                return $this->render('budget_income_plan/index.html.twig', [
                    'datas' => $data,
                    'form' => $form,
                    'edit'=>$id,
                    'entity'=>'budget_income_plan'
                ]);
    
            }
            $budgetIncomePlan = new BudgetIncomePlan();
            $form = $this->createForm(BudgetIncomePlanType::class, $budgetIncomePlan);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                //  $this->denyAccessUnlessGranted('ad_ds');

                
               $this->em->persist($budgetIncomePlan);
               $this->em->flush();
               $this->addFlash('success', "Registered Successfuly");

    
                return $this->redirectToRoute('app_budget_income_plan_index');
            }
            $queryBuilder=$budgetIncomePlanRepository->filter($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('budget_income_plan/index.html.twig', [
                'datas' => $data,
                'form' => $form,
                'edit'=>false,
                'entity'=>'budget_income_plan'
            ]);
        
       
    }
   

    #[Route('/{id}', name: 'app_budget_income_plan_show', methods: ['GET'])]
    public function show(BudgetIncomePlan $budgetIncomePlan): Response
    {
        return $this->render('budget_income_plan/show.html.twig', [
            'budget_income_plan' => $budgetIncomePlan,
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
