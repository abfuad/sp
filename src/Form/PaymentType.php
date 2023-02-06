<?php

namespace App\Form;

use App\Entity\Payment;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('student',EntityType::class,[
            'class'=>Student::class,
            'choice_label' => 'idNumber',
            'placeholder'=>'choose student id number'
        ])
            ->add('receiptNumber')
            ->add('amount')
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
