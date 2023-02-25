<?php

namespace App\Form;

use App\Entity\Grade;
use App\Entity\PaymentYear;
use App\Entity\Student;
use App\Entity\StudentRegistration;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isFree')
            ->add('year',EntityType::class,[
                'class'=>PaymentYear::class,
                'placeholder'=>'Select  Year'
            ])
            ->add('student',EntityType::class,[
                'class'=>Student::class,
               // 'choice_label' => 'idNumber',
                'placeholder'=>'choose student id number'
            ])
            ->add('grade',EntityType::class,[
                'class'=>Grade::class,
                'placeholder'=>'Select Grade',
             
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StudentRegistration::class,
        ]);
    }
}
