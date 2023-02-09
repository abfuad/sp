<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\StudentRegistration;
use App\Form\StudentRegistrationType;
use App\Repository\PaymentMonthRepository;
use App\Repository\PaymentRepository;
use App\Repository\PaymentSettingRepository;
use App\Repository\StudentRegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/student-reg')]
class StudentRegistrationController extends AbstractController
{
    use BaseControllerTrait;
    #[Route('/', name: 'app_student_registration_index', methods: ['GET'])]
    public function index(StudentRegistrationRepository $studentRegistrationRepository): Response
    {
        return $this->render('student_registration/index.html.twig', [
            'student_registrations' => $studentRegistrationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_student_registration_new', methods: ['GET', 'POST'])]
    public function new(PaymentRepository $paymentRepository,PaymentMonthRepository $paymentMonthRepository,PaymentSettingRepository $paymentSettingRepository,Request $request, StudentRegistrationRepository $studentRegistrationRepository): Response
    {
        $months=$paymentMonthRepository->findAll();
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
