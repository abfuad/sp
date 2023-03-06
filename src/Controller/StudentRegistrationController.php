<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Entity\Payment;
use App\Entity\PaymentYear;
use App\Entity\Student;
use App\Entity\StudentRegistration;
use App\Form\StudentRegistrationType;
use App\Helper\PrintHelper;
use App\Helper\UserHelper;
use App\Repository\PaymentMonthRepository;
use App\Repository\PaymentRepository;
use App\Repository\PaymentSettingRepository;
use App\Repository\StudentRegistrationRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/student-reg')]
class StudentRegistrationController extends AbstractController
{
 
    use BaseControllerTrait;
    #[Route('/', name: 'app_student_registration_index', methods: ['GET', "POST"])]
    public function index(PrintHelper $printHelper, StudentRegistrationRepository $studentRegistrationRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $year=UserHelper::toEth(new DateTime('now'));
         $isfiltered=false;
        $session = $request->getSession();
        $activeYear=$this->em->getRepository(PaymentYear::class)->findOneBy(['code'=>$year]);
        $grades=$this->em->getRepository(Grade::class)->findAll();
       
        $form = $this->createFormBuilder()
            ->setMethod("GET")
            ->add('year', EntityType::class, [
                'class' => PaymentYear::class,
                'placeholder' => 'Select Entrance year',
                'required' => false,
                'empty_data' => $activeYear?$activeYear->getId():null,
                ])

            ->add('grade', EntityType::class, [
                'class' => Grade::class,
                'placeholder' => 'Select Grade',
                'required' => false
            ])
            ->add("gender", ChoiceType::class, ["choices" => ["All" => null, "Male" => "M", "Female" => "F"]]);


        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $isfiltered=$form->get('grade')->getData()?true:false;


            
            $queryBuilder = $studentRegistrationRepository->filter($form->getData());
            $reportQuery = $studentRegistrationRepository->filter($form->getData());
        } else {
            $isfiltered= $request->request->get('name')?true:false;

            $queryBuilder = $studentRegistrationRepository->filter(['name' => $request->request->get('name'),'year'=>$activeYear]);
            $reportQuery = $studentRegistrationRepository->filter(['name' => $request->request->get('name'),'year'=>$activeYear]);
        }

// if(isset($request->query->all()['student'])){
//    dd($isfiltered);
// }
        if ($request->query->get('pdf')) {
            $printHelper->print('student_registration/print.html.twig', [
                "datas" => $reportQuery->getResult()
            ], 'TOWHID SCHOOL STUDENT PAYMENT REPORT', 'landscape', 'A4');
        }
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            18
        );
        return $this->render('student_registration/index.html.twig', [
            'datas' => $data,
            'form' => $form,
            'isfiltered'=>$isfiltered
        ]);
    }

    #[Route('/new', name: 'app_student_registration_new', methods: ['GET', 'POST'])]
    public function new(PaymentRepository $paymentRepository,PaymentMonthRepository $paymentMonthRepository,PaymentSettingRepository $paymentSettingRepository,Request $request, StudentRegistrationRepository $studentRegistrationRepository): Response
    {
        $session = $request->getSession();
        $years=$this->em->getRepository(PaymentYear::class)->findAll();
        $grades=$this->em->getRepository(Grade::class)->findAll();
       
        $months=$paymentMonthRepository->findAll();
        if( $session->get('start') ){
            $year=$this->em->getRepository(PaymentYear::class)->find($session->get('reg-year')->getId());
            $prevCode=((int)$year->getCode())-1;
            
            $prevYear=$this->em->getRepository(PaymentYear::class)->findOneBy(['code'=>$prevCode]);
    
            $grade=$this->em->getRepository(Grade::class)->find($session->get('reg-grade')->getId());
            $prevgrade=$this->em->getRepository(Grade::class)->find($session->get('prevgrade')->getId());
            $registrations=$studentRegistrationRepository->findBy(['grade'=>$prevgrade,'year'=>$prevYear]);
           
                $students=$this->em->getRepository(Student::class)->filter(['not-registered'=>true])->getResult();
                
                foreach ($students as $student) {
                    $register=$studentRegistrationRepository->findOneBy(['grade'=>$student->getGrade(),'year'=>$student->getEntranceYear(),'student'=>$student]);

                    if(!$register){
                    
                        $register=new StudentRegistration();
                        $register->setGrade($student->getGrade());
                        $register->setYear($student->getEntranceYear());
                        $register->setStudent($student);
                        $this->em->persist($register);
                        $this->em->flush();
                    }

                    
                }
                  
                

         
           

        $studentRegistration = new StudentRegistration();
        $form = $this->createForm(StudentRegistrationType::class, $studentRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reg=$studentRegistrationRepository->findOneBy(['student'=>$studentRegistration->getStudent(),'year'=>$studentRegistration->getYear()],['id'=>'DESC']);
            $setting=$paymentSettingRepository->findOneBy(['year'=>$studentRegistration->getYear()]);
           
            if($reg){
                $this->addFlash('warning','sorry this student is already registered');
                return $this->redirectToRoute('app_student_registration_new', [], Response::HTTP_SEE_OTHER);
            }
            $studentRegistrationRepository->save($studentRegistration, true);
            foreach ($months as $month) {
               $pay=$paymentRepository->findOneBy(['student'=>$studentRegistration->getStudent(),'month'=>$month,'registration'=>$studentRegistration]);

               if(!$pay){
                $payment=new Payment();
                $payment->setRegistration($studentRegistration);
                $payment->setStudent($studentRegistration->getStudent());
                $payment->setIsPaid(false);
                $payment->setMonth($month);
                $payment->setPriceSetting($setting);
                $this->em->persist($payment);
                $this->em->flush();

               }
            }
            $this->addFlash('success','successfuly registered');

            return $this->redirectToRoute('app_student_registration_new', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('student_registration/new.html.twig', [
            'student_registration' => $studentRegistration,
            'form' => $form,
        ]);
    }
    else{

    
        if($request->request->get('start')){
            $year=$this->em->getRepository(PaymentYear::class)->find($request->request->get('year'));
            $grade=$this->em->getRepository(Grade::class)->find($request->request->get('grade'));
            $prevgrade=$this->em->getRepository(Grade::class)->find($request->request->get('prevgrade'));
      
            $session->set('reg-year', $year);
          
            $session->set('reg-grade', $grade);
            $session->set('prevgrade', $prevgrade);
            $session->set('feetype', $request->request->get('fee-type'));
            $session->set('start', true);
            
            $this->addFlash('success','started successfuly');
            return $this->redirectToRoute('app_student_registration_new', [], Response::HTTP_SEE_OTHER);



        }
        return $this->render('student_registration/start_register.html.twig', [
            'years' => $years,
            
            'grades'=>$grades
        ]);
    }

       
    }

    #[Route('/{id}', name: 'app_student_registration_show', methods: ['GET'])]
    public function show(StudentRegistration $studentRegistration): Response
    {
        return $this->render('student_registration/show.html.twig', [
            'student_registration' => $studentRegistration,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_student_registration_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, StudentRegistration $studentRegistration, StudentRegistrationRepository $studentRegistrationRepository): Response
    {
        $form = $this->createForm(StudentRegistrationType::class, $studentRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $studentRegistrationRepository->save($studentRegistration, true);

            return $this->redirectToRoute('app_student_registration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student_registration/edit.html.twig', [
            'student_registration' => $studentRegistration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_student_registration_delete', methods: ['POST'])]
    public function delete(Request $request, StudentRegistration $studentRegistration, StudentRegistrationRepository $studentRegistrationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$studentRegistration->getId(), $request->request->get('_token'))) {
            $studentRegistrationRepository->remove($studentRegistration, true);
        }

        return $this->redirectToRoute('app_student_registration_index', [], Response::HTTP_SEE_OTHER);
    }
}
