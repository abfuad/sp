<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\PaymentSetting;
use App\Form\PaymentSettingType;
use App\Form\PaymentType;
use App\Repository\PaymentSettingRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payment-setting')]
class PaymentSettingController extends AbstractController
{
   

    use BaseControllerTrait;
    #[Route('/', name: 'app_payment_setting_index', methods: ['GET','POST'])]
    public function index(PaymentSettingRepository $paymentSettingRepository,Request $request, PaginatorInterface $paginator): Response
    {
        
        // $this->denyAccessUnlessGranted('vw_ds');
            if($request->request->get('edit')){
              

                $id=$request->request->get('edit');
                $paymentSetting=$paymentSettingRepository->findOneBy(['id'=>$id]);
                $form = $this->createForm(PaymentSettingType::class, $paymentSetting);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid()) {
                    // $this->denyAccessUnlessGranted('edt_ds');
                    $setting=$paymentSettingRepository->findOneBy(['year'=>$paymentSetting->getYear()]);
                    if($setting && $setting->getId()!=$id){
                        $this->addFlash('danger', "sorry this month  year fee is already registered");
    
        
                    return $this->redirectToRoute('app_payment_setting_index');
    
                    }
                    $this->em->flush();
                    $this->addFlash('success', "Updated Successfuly");

        
                    return $this->redirectToRoute('app_payment_setting_index');
                }
                $queryBuilder=$paymentSettingRepository->filter($request->query->get('search'));
                $data=$paginator->paginate(
                    $queryBuilder,
                    $request->query->getInt('page',1),
                    18
                );
                return $this->render('payment_setting/index.html.twig', [
                    'datas' => $data,
                    'form' => $form,
                    'edit'=>$id,
                    'entity'=>'payment_setting'
                ]);
    
            }
            $paymentSetting = new PaymentSetting();
            $form = $this->createForm(PaymentSettingType::class, $paymentSetting);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $setting=$paymentSettingRepository->findOneBy(['year'=>$paymentSetting->getYear()]);
                if($setting){
                    $this->addFlash('danger', "sorry this   year fee is already registered");

    
                return $this->redirectToRoute('app_payment_setting_index');

                }
                //  $this->denyAccessUnlessGranted('ad_ds');

                
               $this->em->persist($paymentSetting);
               $this->em->flush();
               $this->addFlash('success', "Registered Successfuly");

    
                return $this->redirectToRoute('app_payment_setting_index');
            }
            $queryBuilder=$paymentSettingRepository->filter($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('payment_setting/index.html.twig', [
                'datas' => $data,
                'form' => $form,
                'edit'=>false,
                'entity'=>'payment_setting'
            ]);
        
       
    }

    #[Route('/new', name: 'app_payment_setting_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PaymentSettingRepository $paymentSettingRepository): Response
    {
        $paymentSetting = new PaymentSetting();
        $form = $this->createForm(PaymentSettingType::class, $paymentSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentSettingRepository->save($paymentSetting, true);

            return $this->redirectToRoute('app_payment_setting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('payment_setting/new.html.twig', [
            'payment_setting' => $paymentSetting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payment_setting_show', methods: ['GET'])]
    public function show(PaymentSetting $paymentSetting): Response
    {
        return $this->render('payment_setting/show.html.twig', [
            'payment_setting' => $paymentSetting,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_payment_setting_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PaymentSetting $paymentSetting, PaymentSettingRepository $paymentSettingRepository): Response
    {
        $form = $this->createForm(PaymentSettingType::class, $paymentSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentSettingRepository->save($paymentSetting, true);

            return $this->redirectToRoute('app_payment_setting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('payment_setting/edit.html.twig', [
            'payment_setting' => $paymentSetting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payment_setting_delete', methods: ['POST'])]
    public function delete(Request $request, PaymentSetting $paymentSetting, PaymentSettingRepository $paymentSettingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paymentSetting->getId(), $request->request->get('_token'))) {
            $paymentSettingRepository->remove($paymentSetting, true);
        }

        return $this->redirectToRoute('app_payment_setting_index', [], Response::HTTP_SEE_OTHER);
    }
}
