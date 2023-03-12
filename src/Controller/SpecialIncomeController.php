<?php

namespace App\Controller;

use App\Entity\Budget;
use App\Entity\IncomeSetting;
use App\Entity\IncomeType;
use App\Entity\PaymentYear;
use App\Entity\SpecialIncome;
use App\Form\SpecialIncomeType;
use App\Helper\PrintHelper;
use App\Helper\UserHelper;
use App\Repository\SpecialIncomeRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/special-income')]
class SpecialIncomeController extends AbstractController
{
    use BaseControllerTrait;

    #[Route('/', name: 'app_special_income_index', methods: ['GET','POST'])]
    public function index(PrintHelper $printHelper,SpecialIncomeRepository $specialIncomeRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $queryBuilder =$specialIncomeRepository->filter( $request->request->get('name'));

        if ($request->request->get('pdf')) {
            $printHelper->print('special_income/print.html.twig', [
                "datas" => $queryBuilder->getResult()
            ], 'TOWHID SCHOOL SPECIAL INCOME REPORT', 'landscape', 'A4');
        }
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            18
        );
            
        return $this->render('special_income/index.html.twig', [
            'datas' => $data,    
            ]);
    }

    #[Route('/new', name: 'app_special_income_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SpecialIncomeRepository $specialIncomeRepository): Response
    {
        $year = UserHelper::toEth(new DateTime('now'));
       
        $activeYear = $this->em->getRepository(PaymentYear::class)->findOneBy(['code' => $year]);
        $budget = $this->em->getRepository(Budget::class)->findOneBy(['year' => $activeYear]);
        $feeGroup = $this->em->getRepository(IncomeType::class)->findBy(['source'=>'Others']);
        

        $feeTypes = $this->em->getRepository(IncomeSetting::class)->findBy(['type'=>$feeGroup]);
        
        $specialIncome = new SpecialIncome();
        $form = $this->createForm(SpecialIncomeType::class, $specialIncome,[
            'feetypes'=>$feeTypes
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $specialIncomeRepository->save($specialIncome, true);
            $this->addFlash('success', "Saved Successfuly");

            return $this->redirectToRoute('app_special_income_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('special_income/new.html.twig', [
            'special_income' => $specialIncome,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_special_income_show', methods: ['GET'])]
    public function show(SpecialIncome $specialIncome): Response
    {
        return $this->render('special_income/show.html.twig', [
            'special_income' => $specialIncome,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_special_income_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SpecialIncome $specialIncome, SpecialIncomeRepository $specialIncomeRepository): Response
    {
        
        $year = UserHelper::toEth(new DateTime('now'));
       
        $activeYear = $this->em->getRepository(PaymentYear::class)->findOneBy(['code' => $year]);
        $budget = $this->em->getRepository(Budget::class)->findOneBy(['year' => $activeYear]);
        $feeGroup = $this->em->getRepository(IncomeType::class)->findBy(['source'=>'Others']);
        

        $feeTypes = $this->em->getRepository(IncomeSetting::class)->findBy(['type'=>$feeGroup]);
        
       
        $form = $this->createForm(SpecialIncomeType::class, $specialIncome,[
            'feetypes'=>$feeTypes
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $specialIncomeRepository->save($specialIncome, true);
            $this->addFlash('success', "Updated Successfuly");

            return $this->redirectToRoute('app_special_income_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('special_income/edit.html.twig', [
            'special_income' => $specialIncome,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_special_income_delete', methods: ['POST'])]
    public function delete(Request $request, SpecialIncome $specialIncome, SpecialIncomeRepository $specialIncomeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$specialIncome->getId(), $request->request->get('_token'))) {
            $specialIncomeRepository->remove($specialIncome, true);
        }
        $this->addFlash('success', "Deleted Successfuly");


        return $this->redirectToRoute('app_special_income_index', [], Response::HTTP_SEE_OTHER);
    }
}
