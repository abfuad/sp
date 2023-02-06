<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Entity\Payment;
use App\Entity\PaymentMonth;
use App\Entity\PaymentSetting;
use App\Entity\StudentRegistration;
use App\Form\PaymentType;
use App\Repository\PaymentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payment')]
class PaymentController extends AbstractController
{
    use BaseControllerTrait;
    #[Route('/', name: 'app_payment_index', methods: ['GET'])]
    public function index(Request $request,PaymentRepository $paymentRepository,PaginatorInterface $paginator): Response
    {
        $queryBuilder = $paymentRepository->filter(['name' => $request->request->get('name')]);
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            18
        );
        return $this->render('payment/index.html.twig', [
            'datas' => $data,
        ]);
    }

    #[Route('/new', name: 'app_payment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PaymentRepository $paymentRepository): Response
    {
        $session = $request->getSession();
        $paymentSetting=$this->em->getRepository(PaymentSetting::class)->findAll();
        $months=$this->em->getRepository(PaymentMonth::class)->findAll();
        $grades=$this->em->getRepository(Grade::class)->findAll();
     
        if( $session->get('year') &&  $session->get('month')){
            if($request->request->get('close')){
               
                $session->remove('year');
                $session->remove('month');
                $session->remove('grade');


                
                $this->addFlash('success',' successfuly Exit');
                return $this->redirectToRoute('app_payment_new', [], Response::HTTP_SEE_OTHER);
            }
            $month=$session->get('month');
            $grade=$session->get('grade');

            $year=$this->em->getRepository(PaymentSetting::class)->find($session->get('year')->getId());
            $month=$this->em->getRepository(PaymentMonth::class)->find($session->get('month')->getId());
            $grade=$this->em->getRepository(Grade::class)->find($session->get('grade')->getId());



            $payment = new Payment();
            $form = $this->createForm(PaymentType::class, $payment);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $reg=$this->em->getRepository(StudentRegistration::class)->findOneBy(['grade'=>$grade,'student'=>$payment->getStudent(),'grade'=>$grade],['id'=>'DESC']);
                if(!$reg){
                    $this->addFlash('danger',' sorry this student is not registered');

                    return $this->redirectToRoute('app_payment_new', [], Response::HTTP_SEE_OTHER);

                }
                if($reg && $reg->isIsFree()){
                    $this->addFlash('warning',' sorry this student allowed for free');
                    return $this->redirectToRoute('app_payment_new', [], Response::HTTP_SEE_OTHER);

                }
                $pay=$this->em->getRepository(Payment::class)->findOneBy(['month'=>$month,'registration'=>$reg,'priceSetting'=>$year],['id'=>'DESC']);
                if($pay && $pay->isIsPaid()){
                    $this->addFlash('warning',' sorry this student is already payed');
                    return $this->redirectToRoute('app_payment_new', [], Response::HTTP_SEE_OTHER);
                }
                if($pay){
                    $pay->setIsPaid(true);
                    $pay->setAmount($payment->getAmount());
                    $pay->setCreatedBy($this->getUser());
                    $pay->setReceiptNumber($payment->getReceiptNumber());
                    $paymentRepository->save($pay, true);

                }
                

                // $payment->setRegistration($reg);
                // $payment->setPriceSetting($year);
                
                // $payment->setMonth($month);
                // $paymentRepository->save($payment, true);
                $this->addFlash('success','  payed successfuly');
                return $this->redirectToRoute('app_payment_index', [], Response::HTTP_SEE_OTHER);
            }
    
            return $this->render('payment/new.html.twig', [
                'payment' => $payment,
                'form' => $form,
                'month'=>$month,
                'year'=>$year,
                'grade'=>$grade
            ]);
        }
        else{
            if($request->request->get('start')){
                $setting=$this->em->getRepository(PaymentSetting::class)->find($request->request->get('year'));
                $mont=$this->em->getRepository(PaymentMonth::class)->find($request->request->get('month'));
                $grade=$this->em->getRepository(Grade::class)->find($request->request->get('grade'));

                $session->set('year', $setting);
                $session->set('month', $mont);
                $session->set('grade', $grade);


                $this->addFlash('success','started successfuly');
                return $this->redirectToRoute('app_payment_new', [], Response::HTTP_SEE_OTHER);

    
    
            }
            return $this->render('payment/start_session.html.twig', [
                'years' => $paymentSetting,
                'months' => $months,
                'grades'=>$grades
            ]);
        }
        
    }

    #[Route('/{id}', name: 'app_payment_show', methods: ['GET'])]
    public function show(Payment $payment): Response
    {
        return $this->render('payment/show.html.twig', [
            'payment' => $payment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_payment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Payment $payment, PaymentRepository $paymentRepository): Response
    {
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentRepository->save($payment, true);

            return $this->redirectToRoute('app_payment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('payment/edit.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payment_delete', methods: ['POST'])]
    public function delete(Request $request, Payment $payment, PaymentRepository $paymentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$payment->getId(), $request->request->get('_token'))) {
            $paymentRepository->remove($payment, true);
        }

        return $this->redirectToRoute('app_payment_index', [], Response::HTTP_SEE_OTHER);
    }
}
