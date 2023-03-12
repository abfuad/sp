<?php

namespace App\Controller;

use App\Entity\Budget;
use App\Entity\IncomeSetting;
use App\Entity\IncomeType;
use App\Entity\PaymentYear;
use App\Entity\PenalityFee;
use App\Form\PenalityFeeType;
use App\Helper\PrintHelper;
use App\Helper\UserHelper;
use App\Repository\PenalityFeeRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/penality/fee')]
class PenalityFeeController extends AbstractController
{
    use BaseControllerTrait;
    #[Route('/', name: 'app_penality_fee_index', methods: ['GET','POST'])]
    public function index(PrintHelper $printHelper,PenalityFeeRepository $penalityFeeRepository,Request $request,PaginatorInterface $paginator): Response
    { $form = $this->createFormBuilder()
        ->setMethod("GET")

        ->add("gender", ChoiceType::class, ["choices" => ["All" => null, "Male" => "M", "Female" => "F"]])
        ->add("status", ChoiceType::class, ["choices" => ["All" => null, "Paid" => 1, "Not Paid" => 0]]);
    $form = $form->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $queryBuilder = $penalityFeeRepository->filter($form->getData(), $this->getUser());
    } else
        $queryBuilder = $penalityFeeRepository->filter(['name' => $request->request->get('name')]);
        if ($request->query->get('pdf')) {
            $printHelper->print('penality_fee/print.html.twig', [
                "datas" => $queryBuilder->getResult()
            ], 'TOWHID SCHOOL EMPLOYEE PENALITY REPORT', 'landscape', 'A4');
        }

    $data = $paginator->paginate(
        $queryBuilder,
        $request->query->getInt('page', 1),
        18
    );
        return $this->render('penality_fee/index.html.twig', [
            'datas' => $data,
            'form'=>$form
        ]);
    }

    #[Route('/new', name: 'app_penality_fee_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PenalityFeeRepository $penalityFeeRepository): Response
    {
        $year = UserHelper::toEth(new DateTime('now'));
       
        $activeYear = $this->em->getRepository(PaymentYear::class)->findOneBy(['code' => $year]);
        $budget = $this->em->getRepository(Budget::class)->findOneBy(['year' => $activeYear]);
        $feeGroup = $this->em->getRepository(IncomeType::class)->findBy(['source'=>'Employee']);

        $feeTypes = $this->em->getRepository(IncomeSetting::class)->findBy(['type'=>$feeGroup]);
        
        $penalityFee = new PenalityFee();
        $form = $this->createForm(PenalityFeeType::class, $penalityFee,[
            'budget'=>$budget,
            'feetypes'=>$feeTypes
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $penalityFeeRepository->save($penalityFee, true);
            $this->addFlash('success', "Saved Successfuly");

            return $this->redirectToRoute('app_penality_fee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('penality_fee/new.html.twig', [
            'penality_fee' => $penalityFee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_penality_fee_show', methods: ['GET'])]
    public function show(PenalityFee $penalityFee): Response
    {
        return $this->render('penality_fee/show.html.twig', [
            'penality_fee' => $penalityFee,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_penality_fee_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PenalityFee $penalityFee, PenalityFeeRepository $penalityFeeRepository): Response
    {
        $year = UserHelper::toEth(new DateTime('now'));
       
        $activeYear = $this->em->getRepository(PaymentYear::class)->findOneBy(['code' => $year]);
        $budget = $this->em->getRepository(Budget::class)->findOneBy(['year' => $activeYear]);
        $feeGroup = $this->em->getRepository(IncomeType::class)->findBy(['source'=>'Employee']);

        $feeTypes = $this->em->getRepository(IncomeSetting::class)->findBy(['type'=>$feeGroup]);
        
        $form = $this->createForm(PenalityFeeType::class, $penalityFee,[
            'budget'=>$budget,
            'feetypes'=>$feeTypes
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $penalityFeeRepository->save($penalityFee, true);
            $this->addFlash('success', "Updated Successfuly");


            return $this->redirectToRoute('app_penality_fee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('penality_fee/edit.html.twig', [
            'penality_fee' => $penalityFee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_penality_fee_delete', methods: ['POST'])]
    public function delete(Request $request, PenalityFee $penalityFee, PenalityFeeRepository $penalityFeeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$penalityFee->getId(), $request->request->get('_token'))) {
            $penalityFeeRepository->remove($penalityFee, true);
        }
        $this->addFlash('success', "Deleted Successfuly");


        return $this->redirectToRoute('app_penality_fee_index', [], Response::HTTP_SEE_OTHER);
    }
}
