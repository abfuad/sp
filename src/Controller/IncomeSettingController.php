<?php

namespace App\Controller;

use App\Entity\IncomeSetting;
use App\Form\IncomeSettingType;
use App\Repository\IncomeSettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/income/setting')]
class IncomeSettingController extends AbstractController
{
    #[Route('/', name: 'app_income_setting_index', methods: ['GET'])]
    public function index(IncomeSettingRepository $incomeSettingRepository): Response
    {
        return $this->render('income_setting/index.html.twig', [
            'income_settings' => $incomeSettingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_income_setting_new', methods: ['GET', 'POST'])]
    public function new(Request $request, IncomeSettingRepository $incomeSettingRepository): Response
    {
        $incomeSetting = new IncomeSetting();
        $form = $this->createForm(IncomeSettingType::class, $incomeSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $incomeSettingRepository->save($incomeSetting, true);

            return $this->redirectToRoute('app_income_setting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('income_setting/new.html.twig', [
            'income_setting' => $incomeSetting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_income_setting_show', methods: ['GET'])]
    public function show(IncomeSetting $incomeSetting): Response
    {
        return $this->render('income_setting/show.html.twig', [
            'income_setting' => $incomeSetting,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_income_setting_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, IncomeSetting $incomeSetting, IncomeSettingRepository $incomeSettingRepository): Response
    {
        $form = $this->createForm(IncomeSettingType::class, $incomeSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $incomeSettingRepository->save($incomeSetting, true);

            return $this->redirectToRoute('app_income_setting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('income_setting/edit.html.twig', [
            'income_setting' => $incomeSetting,
            'form' => $form,
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
