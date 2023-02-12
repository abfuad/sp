<?php

namespace App\Controller;

use App\Entity\IncomeType;
use App\Form\IncomeTypeType;
use App\Repository\IncomeTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/income/type')]
class IncomeTypeController extends AbstractController
{
    #[Route('/', name: 'app_income_type_index', methods: ['GET'])]
    public function index(IncomeTypeRepository $incomeTypeRepository): Response
    {
        return $this->render('income_type/index.html.twig', [
            'income_types' => $incomeTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_income_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, IncomeTypeRepository $incomeTypeRepository): Response
    {
        $incomeType = new IncomeType();
        $form = $this->createForm(IncomeTypeType::class, $incomeType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $incomeTypeRepository->save($incomeType, true);

            return $this->redirectToRoute('app_income_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('income_type/new.html.twig', [
            'income_type' => $incomeType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_income_type_show', methods: ['GET'])]
    public function show(IncomeType $incomeType): Response
    {
        return $this->render('income_type/show.html.twig', [
            'income_type' => $incomeType,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_income_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, IncomeType $incomeType, IncomeTypeRepository $incomeTypeRepository): Response
    {
        $form = $this->createForm(IncomeTypeType::class, $incomeType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $incomeTypeRepository->save($incomeType, true);

            return $this->redirectToRoute('app_income_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('income_type/edit.html.twig', [
            'income_type' => $incomeType,
            'form' => $form,
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
