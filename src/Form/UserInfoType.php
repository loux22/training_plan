<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('surname', TextType::class, [
                'attr' => [
                    'placeholder' => "Entrez votre prénom",
                ]
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => "Entrez votre nom",
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => "Entrez votre email",
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe n\'est pas confirmé.',

                'first_options' => [
                    'attr' => [
                        'placeholder' => "Entrez votre mot de passe",
                    ]
                ],
                'second_options' => [
                    'attr' => [
                        'placeholder' => "Répetez votre mot de passe",
                    ]
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
