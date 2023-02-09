<?php

namespace App\Form;

use App\Entity\Grade;
use App\Entity\PaymentYear;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('idNumber',null,[
                'required'=>false
            ])
            ->add('isOrphan')
            ->add('profile')
            ->add('entranceYear',EntityType::class,[
                'class'=>PaymentYear::class,
                'placeholder'=>'Select Entrance Year'
            ])
            ->add('grade',EntityType::class,[
                'class'=>Grade::class,
                'placeholder'=>'Select Grade',
             
            ])
            
        
        ->add('status', ChoiceType::class,["choices" => Student::STATUS,"placeholder"=>"Select Status"]);

        ;
        BaseFormType::userForm($builder);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
