<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $user=$options['user'];

        $builder
            ->add('address', EntityType::class,[
                'label' => "Addresse",
                'attr' => [
                    'placeholder' => "Sélectionner une addresse",
                ],
                'class'=>Address::class,
                'required'=>true,
                'choices'=>$user->getAddresses(),
                'multiple'=>false,
            ])
            ->add('carrier', EntityType::class,[
                'label' => "Livreur",
                'attr' => [
                    'placeholder' => "Sélectionner un livreur",
                ],
                'class'=>Carrier::class,
                'required'=>true,
                'multiple'=>false,
            ])
            ->add('information', TextareaType::class,[
                'required'=>false,
                'label' => "Informations supplémentaires",
                'attr' => [
                    'placeholder' => "...",
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' =>User::class,
        ]);
    }
}
