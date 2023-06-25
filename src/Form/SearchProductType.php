<?php

namespace App\Form;

use App\Entity\Categories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categories', EntityType::class, [
                'class'=>Categories::class,
                'label'=>false,
                'required'=>false,
                'multiple'=>true,
                'attr'=>[
                    'class'=> 'js-categories-multiple'
                ]
            ])
            ->add('minprice', IntegerType::class, [
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Min ...'
                ]
            ])
            ->add('maxprice', IntegerType::class, [
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Max ...'
                ]
            ])
            ->add('tags', TextType::class, [
                'label'=>false,
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Tags...'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
