<?php

namespace App\Controller;

use App\Entity\PaymentYear;
use App\Form\PaymentYearType;
use App\Repository\PaymentYearRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payment-year')]
class PaymentYearController extends AbstractController
{
    
 

    use BaseControllerTrait;
    #[Route('/', name: 'app_payment_year_index', methods: ['GET','POST'])]
    public function index(PaymentYearRepository $paymentYearRepository,Request $request, PaginatorInterface $paginator): Response
    {
        
        // $this->denyAccessUnlessGranted('vw_ds');
            if($request->request->get('edit')){
              

                $id=$request->request->get('edit');
                $location=$paymentYearRepository->findOneBy(['id'=>$id]);
                $form = $this->createForm(PaymentYearType::class, $location);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid()) {
                    // $this->denyAccessUnlessGranted('edt_ds');
                   
                    $this->em->flush();
                    $this->addFlash('success', "Updated Successfuly");

        
                    return $this->redirectToRoute('app_payment_year_index');
                }
                $queryBuilder=$paymentYearRepository->filter($request->query->get('search'));
                $data=$paginator->paginate(
                    $queryBuilder,
                    $request->query->getInt('page',1),
                    18
                );
                return $this->render('payment_year/index.html.twig', [
                    'datas' => $data,
                    'form' => $form,
                    'edit'=>$id,
                    'entity'=>'payment_year'
                ]);
    
            }
            $paymentYear = new PaymentYear();
            $form = $this->createForm(PaymentYearType::class, $paymentYear);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                //  $this->denyAccessUnlessGranted('ad_ds');

                
               $this->em->persist($paymentYear);
               $this->em->flush();
               $this->addFlash('success', "Registered Successfuly");

    
                return $this->redirectToRoute('app_payment_year_index');
            }
            $queryBuilder=$paymentYearRepository->filter($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('payment_year/index.html.twig', [
                'datas' => $data,
                'form' => $form,
                'edit'=>false,
                'entity'=>'payment_year'
            ]);
        
       
    }
    #[Route('/{id}', name: 'app_payment_year_delete', methods: ['POST'])]
    public function delete(Request $request, PaymentYear $paymentYear, PaymentYearRepository $paymentYearRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paymentYear->getId(), $request->request->get('_token'))) {
            $paymentYearRepository->remove($paymentYear, true);
        }

        return $this->redirectToRoute('app_payment_year_index', [], Response::HTTP_SEE_OTHER);
    }
}
