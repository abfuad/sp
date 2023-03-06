<?php

namespace App\Form;

use App\Entity\PaymentYear;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentYearType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        BaseFormType::addCommonForm($builder);
        // $builder->add('startAt', DateType::class, [

        //     'widget' => 'single_text',
        //     'required'=>false
        // ])
        // ->add('endAt', DateType::class, [
        //     // renders it as a single text box
        //     'widget' => 'single_text',
        //     'required'=>false
        // ])
        // ;
        

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaymentYear::class,
        ]);
    }
}
