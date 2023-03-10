<?php

namespace App\Controller;

use App\Entity\Budget;
use App\Entity\BudgetIncomePlan;
use App\Entity\Grade;
use App\Entity\Income;
use App\Entity\IncomeSetting;
use App\Entity\Payment;
use App\Entity\PaymentYear;
use App\Entity\StudentRegistration;
use App\Form\IncomeType;
use App\Helper\PrintHelper;
use App\Repository\IncomeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Andegna\DateTime as AD;
use Andegna\DateTimeFactory ;
use App\Entity\IncomeType as EntityIncomeType;
use App\Helper\UserHelper;
use DateTime;

#[Route('/income')]
class IncomeController extends AbstractController
{
    use BaseControllerTrait;
    #[Route('/', name: 'app_income_index', methods: ['GET', 'POST'])]
    public function index(IncomeRepository $incomeRepository, Request $request, PaginatorInterface $paginator, PrintHelper $printHelper): Response
    {
        $year = UserHelper::toEth(new DateTime('now'));

        $activeYear = $this->em->getRepository(PaymentYear::class)->findOneBy(['code' => $year]);
        $budget = $this->em->getRepository(Budget::class)->findOneBy(['year' => $activeYear]);
        $feeGroup = $this->em->getRepository(EntityIncomeType::class)->findBy(['source'=>'Student']);
        

        $feeTypes = $this->em->getRepository(IncomeSetting::class)->findBy(['type'=>$feeGroup]);
        
        $form = $this->createFormBuilder()
            ->setMethod("GET")
            ->add('year', EntityType::class, [
                'class' => PaymentYear::class,
                'placeholder' => 'Select year',
                'empty_data' => $activeYear ? $activeYear->getId() : null,
                'required' => false,
                'data'=>$activeYear ? $activeYear: null
            ])
            ->add('type', EntityType::class, [
                'class' => IncomeSetting::class,
                'placeholder' => 'Select Fee Type',
                'required' => false,
                'choices' => $feeTypes
            ])
            ->add('grade', EntityType::class, [
                'class' => Grade::class,
                'placeholder' => 'Select Grade',
                'required' => false
            ])


            ->add("status", ChoiceType::class, ["choices" => ["All" => null, "Paid" => 1, "UnPaid" => 0]]);
        $form = $form->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $queryBuilder = $incomeRepository->filter($form->getData());
        } else
            $queryBuilder = $incomeRepository->filter(['name' => $request->request->get('name')]);

        if ($request->query->get('pdf')) {
            $printHelper->print('payment/print.html.twig', [
                "datas" => $queryBuilder->getResult()
            ], 'TOWHID SCHOOL STUDENT PAYMENT REPORT', 'landscape', 'A4');
        }

        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            18
        );
        return $this->render('income/index.html.twig', [

            'datas' => $data,
            'form' => $form

        ]);
    }

    #[Route('/new', name: 'app_income_new', methods: ['GET', 'POST'])]
    public function new(Request $request, IncomeRepository $incomeRepository, PaginatorInterface $paginator): Response
    {
        $ethipic = new AD(new DateTime('now'));
           
        $session = $request->getSession();
        $grades = $this->em->getRepository(Grade::class)->findAll();
        $feeGroup = $this->em->getRepository(EntityIncomeType::class)->findBy(['source'=>'Student']);

        $feeTypes = $this->em->getRepository(IncomeSetting::class)->findBy(['type'=>$feeGroup]);
        
        $years = $this->em->getRepository(PaymentYear::class)->findAll();
        
        if ($session->get('income-start')) {

            $year = $this->em->getRepository(PaymentYear::class)->find($session->get('income-year')->getId());
            $grade = $this->em->getRepository(Grade::class)->find($session->get('income-grade')->getId());
            $feeType = $this->em->getRepository(IncomeSetting::class)->find($session->get('income-feetype')->getId());


            if ($request->request->get('close')) {

                $session->remove('income-start');
                $session->remove('income-year');
                $session->remove('income-feetype');
                $session->remove('income-grade');
                $this->addFlash('success', 'successfuly Exit');
                return $this->redirectToRoute('app_income_new', [], Response::HTTP_SEE_OTHER);
            }

            if ($request->request->get('cancel')) {

                $income = $this->em->getRepository(Income::class)->find($request->request->get('cancel'));
                $income->setReceiptNumber(null);
                $this->em->flush();
                $this->addFlash('success', 'successfuly Canceled');
                // return $this->redirectToRoute('app_income_new', [], Response::HTTP_SEE_OTHER);
            }


            if ($request->request->get('paid')) {
                $receiptno = $request->request->get('receipt');
                $income = $this->em->getRepository(Income::class)->findOneBy(['receiptNumber' => $receiptno]);
                if ($income) {
                    $this->addFlash('danger', 'sorry this receipt number is already registered');
                    //  return $this->redirectToRoute('app_income_new', [], Response::HTTP_SEE_OTHER);
                } else {
                    $income = $this->em->getRepository(Income::class)->find($request->request->get('paid'));
                    $budget = $this->em->getRepository(Budget::class)->findOneBy(['year' => $year]);

                    $incomePlan = $this->em->getRepository(BudgetIncomePlan::class)->findOneBy(['type' => $feeType->gettype(), 'budget' => $budget]);

                    $income->setReceiptNumber($receiptno);
                    $income->setIncomePlan($incomePlan);
                    $this->em->flush();

                    $this->addFlash('success', 'successfuly Paid');
                }
            }



            $queryBuilder = $this->em->getRepository(Income::class)->filter(['type' => $feeType, 'year' => $year, 'grade' => $grade, 'name' => $request->request->get('name')]);
            $data = $paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page', 1),
                18
            );
            return $this->render('income/new.html.twig', [
                'year' => $year,
                'grade' => $grade,
                'fee_type' => $feeType,
                'datas' => $data

            ]);
        }

        if ($request->request->get('start')) {
            $grade = $this->em->getRepository(Grade::class)->find($request->request->get('grade'));
            $year = $this->em->getRepository(PaymentYear::class)->find($request->request->get('year'));
            $feeType = $this->em->getRepository(IncomeSetting::class)->find($request->request->get('fee-type'));
            $registrations = $this->em->getRepository(StudentRegistration::class)->findBy(['grade' => $grade, 'year' => $year]);
            // if(count($registrations)<1){
            //     $this->addFlash('danger', 'sorry! no student is registered in the grade and year u selected please first register it!');
            // return $this->redirectToRoute('app_income_new', [], Response::HTTP_SEE_OTHER);


            // }
            foreach ($registrations as $register) {
                $income = $this->em->getRepository(Income::class)->findOneBy(['registration' => $register, 'type' => $feeType]);
                if (!$income) {
                    $income = new Income();
                    $income->setStudent($register->getStudent());
                    $income->setRegistration($register);
                    $income->setType($feeType);
                    
                    $income->setYear($year);
                    $income->setAmount($feeType->getFee());
                    $this->em->persist($income);
                    $this->em->flush();
                }
            }

            $session->set('income-year', $year);

            $session->set('income-grade', $grade);

            $session->set('income-feetype', $feeType);
            $session->set('income-start', true);

            $this->addFlash('success', 'started successfuly');
            return $this->redirectToRoute('app_income_new', [], Response::HTTP_SEE_OTHER);


            $incomes = $this->em->getRepository(Income::class)->filter(['type' => $feeType, 'year' => $year, 'grade' => $grade, 'isfree' => 0])->getResult();
        }
        return $this->render('income/start_session.html.twig', [
            'years' => $years,
            'grades' => $grades,
            'fee_types' => $feeTypes

        ]);
    }

    #[Route('/{id}', name: 'app_income_show', methods: ['GET'])]
    public function show(Income $income): Response
    {
        return $this->render('income/show.html.twig', [
            'income' => $income,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_income_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Income $income, IncomeRepository $incomeRepository): Response
    {
        $form = $this->createForm(IncomeType::class, $income);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $incomeRepository->save($income, true);

            return $this->redirectToRoute('app_income_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('income/edit.html.twig', [
            'income' => $income,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_income_delete', methods: ['POST'])]
    public function delete(Request $request, Income $income, IncomeRepository $incomeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $income->getId(), $request->request->get('_token'))) {
            $incomeRepository->remove($income, true);
        }

        return $this->redirectToRoute('app_income_index', [], Response::HTTP_SEE_OTHER);
    }
}
