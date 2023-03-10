<?php

namespace App\Form;

use App\Entity\IncomeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncomeTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $role=["Student" => "Student","Employee"=>"Employee","Others"=>"Others"];

        BaseFormType::addCommonForm($builder);
        $builder ->add('source', ChoiceType::class,["choices" => $role,"placeholder"=>"Select Source"]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IncomeType::class,
        ]);
    }
}
