<?php

namespace App\Form;

use App\Entity\Budget;
use App\Entity\PaymentYear;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BudgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          
            ->add('planValue',MoneyType::class,[
                'currency'=>'ETB',
                // 'grouping'=>true
            ])
            // ->add('actualValue')#
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
            'data_class' => Budget::class,
        ]);
    }
}
