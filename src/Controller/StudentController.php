<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Entity\PaymentYear;
use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/student')]
class StudentController extends AbstractController
{
       use BaseControllerTrait;
    #[Route('/', name: 'app_student_index', methods: ['GET',"POST"])]
    public function index(StudentRepository $studentRepository,Request $request, PaginatorInterface $paginator): Response
    { 
        if($request->request->get('generate')){
            $students=$studentRepository->findBy(['idNumber'=>null]);
            foreach ($students as $student) {
                $count=$studentRepository->getCount()+1;
                $idnumber="Ru".$this->generatePin($count,4);
                $student->setIdNumber($idnumber);
                $this->em->flush();
               
                
            }
            $this->addFlash('success','successfuly generated');
            return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);

        }
        $form = $this->createFormBuilder()
        ->setMethod("GET")
        ->add('year', EntityType::class, [
            'class' => PaymentYear::class,
            'placeholder' => 'Select Entrance year',
            'required' => false
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
            $queryBuilder = $studentRepository->filter($form->getData());
        } else
            $queryBuilder = $studentRepository->filter(['name' => $request->request->get('name')]);
            $data = $paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page', 1),
                18
            );
        return $this->render('student/index.html.twig', [
            'datas' => $data,
            'form'=>$form
        ]);
    }

    #[Route('/new', name: 'app_student_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StudentRepository $studentRepository): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $studentRepository->save($student, true);
            $this->addFlash('success','successfuly registered');

            return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('student/new.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_student_show', methods: ['GET'])]
    public function show(Student $student): Response
    {
        return $this->render('student/show.html.twig', [
            'student' => $student,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_student_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Student $student, StudentRepository $studentRepository): Response
    {
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $studentRepository->save($student, true);
            $this->addFlash('success','successfuly Updated');

            return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('student/edit.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_student_delete', methods: ['POST'])]
    public function delete(Request $request, Student $student, StudentRepository $studentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$student->getId(), $request->request->get('_token'))) {
            $studentRepository->remove($student, true);
        }

        return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
    }
    private function generatePin($number, $max)
    {
        $len = strlen($number);
        while (strlen($number) < $max) {
            $number = "0" . $number;
        }
        return $number;
    }
}
