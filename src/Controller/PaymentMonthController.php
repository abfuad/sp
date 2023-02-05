<?php

namespace App\Controller;

use App\Entity\PaymentMonth;
use App\Form\PaymentMonthType;
use App\Repository\PaymentMonthRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payment-month')]
class PaymentMonthController extends AbstractController
{
    use BaseControllerTrait;
    #[Route('/', name: 'app_payment_month_index', methods: ['GET','POST'])]
    public function index(PaymentMonthRepository $paymentMonthRepository,Request $request, PaginatorInterface $paginator): Response
    {
        
        // $this->denyAccessUnlessGranted('vw_ds');
            if($request->request->get('edit')){
              

                $id=$request->request->get('edit');
                $paymentMonth=$paymentMonthRepository->findOneBy(['id'=>$id]);
                $form = $this->createForm(PaymentMonthType::class, $paymentMonth);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid()) {
                    // $this->denyAccessUnlessGranted('edt_ds');
                   
                    $this->em->flush();
                    $this->addFlash('success', "Updated Successfuly");

        
                    return $this->redirectToRoute('app_payment_month_index');
                }
                $queryBuilder=$paymentMonthRepository->filter($request->query->get('search'));
                $data=$paginator->paginate(
                    $queryBuilder,
                    $request->query->getInt('page',1),
                    18
                );
                return $this->render('payment_month/index.html.twig', [
                    'datas' => $data,
                    'form' => $form,
                    'edit'=>$id,
                    'entity'=>'payment_month'
                ]);
    
            }
            $paymentMonth = new PaymentMonth();
            $form = $this->createForm(PaymentMonthType::class, $paymentMonth);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                //  $this->denyAccessUnlessGranted('ad_ds');

                
               $this->em->persist($paymentMonth);
               $this->em->flush();
               $this->addFlash('success', "Registered Successfuly");

    
                return $this->redirectToRoute('app_payment_month_index');
            }
            $queryBuilder=$paymentMonthRepository->filter($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('payment_month/index.html.twig', [
                'datas' => $data,
                'form' => $form,
                'edit'=>false,
                'entity'=>'payment_month'
            ]);
        
       
    }

    #[Route('/new', name: 'app_payment_month_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PaymentMonthRepository $paymentMonthRepository): Response
    {
        $paymentMonth = new PaymentMonth();
        $form = $this->createForm(PaymentMonthType::class, $paymentMonth);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentMonthRepository->save($paymentMonth, true);

            return $this->redirectToRoute('app_payment_month_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('payment_month/new.html.twig', [
            'payment_month' => $paymentMonth,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payment_month_show', methods: ['GET'])]
    public function show(PaymentMonth $paymentMonth): Response
    {
        return $this->render('payment_month/show.html.twig', [
            'payment_month' => $paymentMonth,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_payment_month_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PaymentMonth $paymentMonth, PaymentMonthRepository $paymentMonthRepository): Response
    {
        $form = $this->createForm(PaymentMonthType::class, $paymentMonth);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentMonthRepository->save($paymentMonth, true);

            return $this->redirectToRoute('app_payment_month_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('payment_month/edit.html.twig', [
            'payment_month' => $paymentMonth,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payment_month_delete', methods: ['POST'])]
    public function delete(Request $request, PaymentMonth $paymentMonth, PaymentMonthRepository $paymentMonthRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paymentMonth->getId(), $request->request->get('_token'))) {
            $paymentMonthRepository->remove($paymentMonth, true);
        }

        return $this->redirectToRoute('app_payment_month_index', [], Response::HTTP_SEE_OTHER);
    }
}
