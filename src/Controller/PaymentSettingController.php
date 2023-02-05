<?php

namespace App\Controller;

use App\Entity\PaymentSetting;
use App\Form\PaymentSettingType;
use App\Repository\PaymentSettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payment/setting')]
class PaymentSettingController extends AbstractController
{
    #[Route('/', name: 'app_payment_setting_index', methods: ['GET'])]
    public function index(PaymentSettingRepository $paymentSettingRepository): Response
    {
        return $this->render('payment_setting/index.html.twig', [
            'payment_settings' => $paymentSettingRepository->findAll(),
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
