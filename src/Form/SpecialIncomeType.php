<?php

namespace App\Form;

use App\Entity\IncomeSetting;
use App\Entity\SpecialIncome;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecialIncomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
           // ->add('code')
            ->add('description')
            ->add('amount')
            ->add('receiptNumber')

            ->add('type',EntityType::class,[
                'class'=>IncomeSetting::class,
                'placeholder'=>'Select Fee Type',
                'choices' =>$options['feetypes']
            ])
            ->add('budget')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SpecialIncome::class,
            'feetypes'=>null

        ]);
        $resolver->setAllowedTypes('feetypes', 'array');

    }
}
