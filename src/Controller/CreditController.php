<?php

namespace App\Controller;

use App\Entity\Credit;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\CreditType;
use App\Helper\PrintHelper;
use App\Repository\CreditRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/credit')]
class CreditController extends AbstractController
{
    use BaseControllerTrait;
    #[Route('/', name: 'app_credit_index', methods: ['GET','POST'])]
    public function index(CreditRepository $creditRepository,PrintHelper $printHelper,Request $request,PaginatorInterface $paginator): Response
    {
        $users=$this->em->getRepository(User::class)->findBy(['isActive'=>true]);
        if($request->request->get('pay')){
        $id=$request->request->get('pay');
        $credit=$creditRepository->find($id);
        $credit->setStatus(1);
        $creditRepository->save($credit,true);
        $this->addFlash('success','Successfuly paid Thank you');
        }
        if($request->request->get('cancel-pay')){
            $id=$request->request->get('cancel-pay');
            $credit=$creditRepository->find($id);
            $credit->setStatus(0);
            $creditRepository->save($credit,true);
            $this->addFlash('success','Payment is Successfuly Cancelled Thank you');
            }
        $form = $this->createFormBuilder()
        ->setMethod("GET")

        ->add("gender", ChoiceType::class, ["choices" => ["All" => null, "Male" => "M", "Female" => "F"]])
        ->add("status", ChoiceType::class, ["choices" => ["All" => null, "Paid" => 1, "Not Paid" => 0]]);
    $form = $form->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $queryBuilder = $creditRepository->findCredit($form->getData(), $this->getUser());
    } else
        $queryBuilder = $creditRepository->findCredit(['name' => $request->request->get('name')]);

        if ($request->query->get('pdf')) {
            $printHelper->print('credit/print.html.twig', [
                "datas" => $queryBuilder->getResult()
            ], 'TOWHID SCHOOL CREDIT REPORT', 'landscape', 'A4');
        }
        if ($request->query->get('salary')) {
            $printHelper->print('credit/print_salary.html.twig', [
                "datas" => $users
            ], 'TOWHID SCHOOL EMPLOYEE SALARY', 'landscape', 'A4');
        }
    $data = $paginator->paginate(
        $queryBuilder,
        $request->query->getInt('page', 1),
        18
    );

   
        return $this->render('credit/index.html.twig', [
            'datas' => $data,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_credit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CreditRepository $creditRepository): Response
    {
        $credit = new Credit();
        $form = $this->createForm(CreditType::class, $credit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $creditRepository->save($credit, true);

            return $this->redirectToRoute('app_credit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('credit/new.html.twig', [
            'credit' => $credit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_credit_show', methods: ['GET'])]
    public function show(Credit $credit): Response
    {
        return $this->render('credit/show.html.twig', [
            'credit' => $credit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_credit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Credit $credit, CreditRepository $creditRepository): Response
    {
        $form = $this->createForm(CreditType::class, $credit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $creditRepository->save($credit, true);

            return $this->redirectToRoute('app_credit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('credit/edit.html.twig', [
            'credit' => $credit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_credit_delete', methods: ['POST'])]
    public function delete(Request $request, Credit $credit, CreditRepository $creditRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$credit->getId(), $request->request->get('_token'))) {
            $creditRepository->remove($credit, true);
        }

        return $this->redirectToRoute('app_credit_index', [], Response::HTTP_SEE_OTHER);
    }
}
