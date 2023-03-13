<?php

namespace App\Form;

use App\Entity\AssetCategory;
use App\Entity\Measure;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssetCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
           BaseFormType::addCommonForm($builder);
           $builder  ->add('unit',EntityType::class,[
            'class'=>Measure::class,
            'placeholder'=>'Select Measurement',
            // 'mapped'=>false,
        ])
        ->add('isFixed',CheckboxType::class,[
            // 'mapped'=>false,
            'required'=>false
        ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AssetCategory::class,
        ]);
    }
}
