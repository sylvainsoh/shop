<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom",
                'attr' => [
                    'placeholder' => 'Votre nom',
                ],
            ])
            ->add('email', TextType::class, [
                'label' => "Email",
                'attr' => [
                    'placeholder' => 'Votre email',
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => "Numero de téléphone",
                'attr' => [
                    'placeholder' => 'Notre numero',
                ],
            ])
            ->add('subject', TextType::class, [
                'label' => "Objet",
                'attr' => [
                    'placeholder' => "Quel est l'objet de votre méssage ? ",
                ],
            ])
            ->add('content', TextareaType::class, [
                'required' => false,
                'label' => "Votre message",
                'attr' => [
                    'placeholder' => "...",
                    'rows'=>8
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
