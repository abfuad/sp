<?php

namespace App\Form;

use App\Entity\Budget;
use App\Entity\IncomeSetting;
use App\Entity\PenalityFee;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PenalityFeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('user',EntityType::class,[
            'class'=> User::class,
            'placeholder'=>'Select Employee'
        ])
        ->add('type',EntityType::class,[
            'class'=>IncomeSetting::class,
            'placeholder'=>'Select Penality Type',
            'choices' =>$options['feetypes']
        ])
        ->add('budget',EntityType::class,[
            'class'=>Budget::class,
            'data'=>$options['budget'],
            'disabled'=>true
        ])
            ->add('amount')
            ->add('receiptNumber')
            ->add('description')    
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PenalityFee::class,
            'budget'=>null,
            'feetypes'=>null
        ]);
        $resolver->setAllowedTypes('budget', 'object');
        $resolver->setAllowedTypes('feetypes', 'array');

    }
}
