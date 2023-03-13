<?php

namespace App\Form;

use App\Entity\Asset;
use App\Entity\AssetCategory;
use App\Entity\Measure;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity')
            
            ->add('category',EntityType::class,[
                'class'=>AssetCategory::class,
                'placeholder'=>'Select asset name',
                // 'mapped'=>false,
            ])
            // ->add('unit',EntityType::class,[
            //     'class'=>Measure::class,
            //     'placeholder'=>'Select Measurement',
            //     'mapped'=>false,
            // ])
            // ->add('isFixed',CheckboxType::class,[
            //     'mapped'=>false,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Asset::class,
        ]);
    }
}
