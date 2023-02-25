<?php

namespace App\Form;

use App\Entity\IncomeSetting;
use App\Entity\IncomeType;
use App\Entity\PaymentYear;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncomeSettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            
            ->add('type',EntityType::class,[
                'class'=>IncomeType::class,
               
                'placeholder'=>'choose Income Type'
            ])
            ->add('year',EntityType::class,[
                'class'=>PaymentYear::class,
               
                'placeholder'=>'choose Year'
            ])
        ;
        BaseFormType::addCommonForm($builder);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IncomeSetting::class,
        ]);
    }
}
