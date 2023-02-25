<?php

namespace App\Controller;

use App\Entity\IncomeType;
use App\Form\IncomeTypeType;
use App\Repository\IncomeTypeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/income-type')]
class IncomeTypeController extends AbstractController
{
   
    use BaseControllerTrait;
    #[Route('/', name: 'app_income_type_index', methods: ['GET','POST'])]
    public function index(IncomeTypeRepository $incomeTypeRepository,Request $request, PaginatorInterface $paginator): Response
    {
        
        // $this->denyAccessUnlessGranted('vw_ds');
            if($request->request->get('edit')){
              

                $id=$request->request->get('edit');
                $incomeType=$incomeTypeRepository->findOneBy(['id'=>$id]);
                $form = $this->createForm(IncomeTypeType::class, $incomeType);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid()) {
                    // $this->denyAccessUnlessGranted('edt_ds');
                   
                    $this->em->flush();
                    $this->addFlash('success', "Updated Successfuly");

        
                    return $this->redirectToRoute('app_income_type_index');
                }
                $queryBuilder=$incomeTypeRepository->filter($request->query->get('search'));
                $data=$paginator->paginate(
                    $queryBuilder,
                    $request->query->getInt('page',1),
                    18
                );
                return $this->render('income_type/index.html.twig', [
                    'datas' => $data,
                    'form' => $form,
                    'edit'=>$id,
                    'entity'=>'income_type'
                ]);
    
            }
            $incomeType = new IncomeType();
            $form = $this->createForm(IncomeTypeType::class, $incomeType);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                //  $this->denyAccessUnlessGranted('ad_ds');

                
               $this->em->persist($incomeType);
               $this->em->flush();
               $this->addFlash('success', "Registered Successfuly");

    
                return $this->redirectToRoute('app_income_type_index');
            }
            $queryBuilder=$incomeTypeRepository->filter($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('income_type/index.html.twig', [
                'datas' => $data,
                'form' => $form,
                'edit'=>false,
                'entity'=>'income_type'
            ]);
        
       
    }

    #[Route('/{id}', name: 'app_income_type_delete', methods: ['POST'])]
    public function delete(Request $request, IncomeType $incomeType, IncomeTypeRepository $incomeTypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$incomeType->getId(), $request->request->get('_token'))) {
            $incomeTypeRepository->remove($incomeType, true);
        }

        return $this->redirectToRoute('app_income_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
