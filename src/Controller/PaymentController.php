<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Entity\Payment;
use App\Entity\PaymentMonth;
use App\Entity\PaymentSetting;
use App\Entity\PaymentYear;
use App\Entity\Student;
use App\Entity\StudentRegistration;
use App\Form\PaymentType;
use App\Helper\PrintHelper;
use App\Repository\PaymentRepository;
use App\Repository\PaymentSettingRepository;
use App\Repository\PaymentYearRepository;
use App\Repository\StudentRegistrationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payment')]
class PaymentController extends AbstractController
{
    use BaseControllerTrait;
    #[Route('/', name: 'app_payment_index', methods: ['GET','POST'])]
    public function index(PaymentYearRepository $paymentYearRepository,PaymentSettingRepository $paymentSettingRepository,PrintHelper $printHelper,Request $request,StudentRegistrationRepository $studentRegistrationRepository,PaymentRepository $paymentRepository,PaginatorInterface $paginator): Response
    {
        $setting=$paymentSettingRepository->findOneBy(['isActive'=>1],['id'=>'DESC']);
        $year=$setting?$paymentYearRepository->find($setting->getYear()->getId()):null;
   
        if ($request->query->get('reset')) {
            return $this->redirectToRoute('app_payment_index', [], Response::HTTP_SEE_OTHER);


        }
        $form = $this->createFormBuilder()
        ->setMethod("GET")
        ->add('year', EntityType::class, [
            'class' => PaymentYear::class,
            'placeholder' => 'Select year',
            'required' => false
        ])
        ->add('month', EntityType::class, [
            'class' => PaymentMonth::class,
            'placeholder' => 'Select month',
            'required' => false
        ])
        ->add('grade', EntityType::class, [
            'class' => Grade::class,
            'placeholder' => 'Select Grade',
            'required' => false
        ])
        ->add('student', EntityType::class, [
            'class' => Student::class,
            'placeholder' => 'Select Student',
        //    'choice_label' => 'idNumber',
            'required' => false
        ])
       
        ->add("status", ChoiceType::class, ["choices" => ["All" => null, "Paid" => Payment::PAID, "UnPaid" => Payment::UNPAID]]);
    $form = $form->getForm();
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $queryBuilder = $studentRegistrationRepository->filter($form->getData(),$year);
        $reportQuery =  $paymentRepository->filter($form->getData(),$year);
       
    } else{
        $queryBuilder = $studentRegistrationRepository->filter(['name' => $request->request->get('name')],$year);
        $reportQuery = $paymentRepository->filter(['name' => $request->request->get('name')],$year);

    }


        if ($request->query->get('pdf')) {
            $printHelper->print('payment/print.html.twig', ["datas" => $reportQuery->getResult()
            ], 'TOWHID SCHOOL STUDENT PAYMENT REPORT', 'landscape', 'A4');


        }
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            18
        );
        return $this->render('payment/index.html.twig', [
            'datas' => $data,
            'form'=>$form
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
            if($request->request->get('undo')){
                $undpay=$this->em->getRepository(Payment::class)->find($request->request->get('undo'));
                $pay=new Payment();
                $pay->setIsPaid(false);
                $pay->setMonth($undpay->getMonth());
                $pay->setPriceSetting($undpay->getPriceSetting());
                $pay->setStudent($undpay->getStudent());
                $pay->setRegistration($undpay->getRegistration());

                 
              
                    $paymentRepository->save($pay, true);
                    $paymentRepository->remove($undpay, true);


                
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
                

                
                $this->addFlash('success','  payed successfuly');
                return $this->redirectToRoute('app_payment_new', [], Response::HTTP_SEE_OTHER);
            }
            $latest=$paymentRepository->getLatest();
    
            return $this->render('payment/new.html.twig', [
                'payment' => $payment,
                'form' => $form,
                'month'=>$month,
                'year'=>$year,
                'grade'=>$grade,
                'datas'=>$latest
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
