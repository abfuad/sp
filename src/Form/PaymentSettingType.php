<?php

namespace App\Form;

use App\Entity\PaymentSetting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentSettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {  
        // BaseFormType::addCommonForm($builder);
        $builder
           
            ->add('amount')
           
            ->add('numberOfMonth')
            ->add('year')
        ;
      
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaymentSetting::class,
        ]);
    }
}
