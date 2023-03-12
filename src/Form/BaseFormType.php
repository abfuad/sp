<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseFormType
{
  
    static public function addCommonForm($builder)
    {
        return  $builder->add('name',TextType::class)
                    
                         ->add('code',TextType::class,[
                            'required'=>false
                         ])
                         ->add('description',TextareaType::class,[
                            'required'=>false
                         ]
                         
                        );
    }
    static public function userForm($builder)
    {
        return  $builder
         ->add('firstName',TextType::class)
         ->add('middleName',TextType::class)
        ->add('lastName',TextType::class)
        ->add('phone',TelType::class,[
            'required'=>false
        ])

    
        ->add('kebele',null,[
            'required'=>false
        ])
        ->add('sex', ChoiceType::class,["choices" => ["Select Sex"=>null,"Male" => "M","Female"=>"F"]])

        ->add('age',IntegerType::class,[
            'required'=>false
        ])
       
                        ;
    }
}
