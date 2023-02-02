<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname', TextType::class, [
                'label' => "Nom de l'addresse",
                'attr' => [
                    'placeholder' => 'Nom descriptif',
                ],
            ])
            ->add('campany', TextType::class, [
                'label' => "Entreprise",
                'attr' => [
                    'placeholder' => "Nom de l'entreprise",
                ],
            ])
            ->add('address', TextareaType::class, [
                'label' => 'Addresse',
                'attr' => [
                    'placeholder' => 'Libellé de votre addresse',
                ],
            ])
            ->add('complement', TextareaType::class, [
                'label' => 'Complément',
                'attr' => [
                    'placeholder' => "Complément d'addresse",
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Vile',
                'attr' => [
                    'placeholder' => 'Nom de la ville',
                ],
            ])
            ->add('codePostal', TextType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => '',
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'placeholder' => 'Numero de téléphone',
                    'class'=>'block'
                ],
            ])
            ->add('country', CountryType::class, [
                'label' => 'Sélectionnez le pays',
                'attr' => [
                    'class' => ' form-control block ',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
