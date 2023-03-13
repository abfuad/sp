<?php

namespace App\Form;

use App\Entity\Measure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeasureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        BaseFormType::addCommonForm($builder);
       
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Measure::class,
        ]);
    }
}
