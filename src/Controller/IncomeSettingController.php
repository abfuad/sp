<?php

namespace App\Controller;

use App\Entity\IncomeSetting;
use App\Form\IncomeSettingType;
use App\Repository\IncomeSettingRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/income-setting')]
class IncomeSettingController extends AbstractController
{

    use BaseControllerTrait;
    #[Route('/', name: 'app_income_setting_index', methods: ['GET','POST'])]
    public function index(IncomeSettingRepository $incomeSettingRepository,Request $request, PaginatorInterface $paginator): Response
    {
        
        // $this->denyAccessUnlessGranted('vw_ds');
            if($request->request->get('edit')){
              

                $id=$request->request->get('edit');
                $incomeSetting=$incomeSettingRepository->findOneBy(['id'=>$id]);
                $form = $this->createForm(IncomeSettingType::class, $incomeSetting);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid()) {
                    // $this->denyAccessUnlessGranted('edt_ds');
                   
                    $this->em->flush();
                    $this->addFlash('success', "Updated Successfuly");

        
                    return $this->redirectToRoute('app_income_setting_index');
                }
                $queryBuilder=$incomeSettingRepository->filter($request->query->get('search'));
                $data=$paginator->paginate(
                    $queryBuilder,
                    $request->query->getInt('page',1),
                    18
                );
                return $this->render('income_setting/index.html.twig', [
                    'datas' => $data,
                    'form' => $form,
                    'edit'=>$id,
                    'entity'=>'income_setting'
                ]);
    
            }
            $incomeSetting = new IncomeSetting();
            $form = $this->createForm(IncomeSettingType::class, $incomeSetting);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                //  $this->denyAccessUnlessGranted('ad_ds');

                
               $this->em->persist($incomeSetting);
               $this->em->flush();
               $this->addFlash('success', "Registered Successfuly");

    
                return $this->redirectToRoute('app_income_setting_index');
            }
            $queryBuilder=$incomeSettingRepository->filter($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('income_setting/index.html.twig', [
                'datas' => $data,
                'form' => $form,
                'edit'=>false,
                'entity'=>'income_setting'
            ]);
        
       
    }

    #[Route('/{id}', name: 'app_income_setting_delete', methods: ['POST'])]
    public function delete(Request $request, IncomeSetting $incomeSetting, IncomeSettingRepository $incomeSettingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$incomeSetting->getId(), $request->request->get('_token'))) {
            $incomeSettingRepository->remove($incomeSetting, true);
        }

        return $this->redirectToRoute('app_income_setting_index', [], Response::HTTP_SEE_OTHER);
    }
}
