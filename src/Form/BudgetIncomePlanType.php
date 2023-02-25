<?php

namespace App\Form;

use App\Entity\BudgetIncomePlan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BudgetIncomePlanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          
            ->add('planValue')
            ->add('budget')
            ->add('type')
        ;
        BaseFormType::addCommonForm($builder);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BudgetIncomePlan::class,
        ]);
    }
}
