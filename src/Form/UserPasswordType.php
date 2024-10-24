<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control mt-4',
                    'placeholder' => 'Mot de passe actuel',
                ],
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'd-none'
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Nouveau mot de passe',
                    ],
                    'label' => 'Votre mot de passe doit contenir plus de 8 caractères avec une minuscule, une majuscule et un caractère spécial.',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                    'constraints' => [
                        new Regex(
                            '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                            'Votre mot de passe doit contenir plus de 8 caractères avec une minuscule, une majuscule et un caractère spécial.'
                        ),
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control mt-4',
                        'placeholder' => 'Confirmer nouveau mot de passe',
                    ],
                    'label' => 'Confirmer mot de passe',
                    'label_attr' => [
                        'class' => 'd-none'
                    ],
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-primary mt-3'
                ],
                'label' => 'Enregistrer'
            ]);
    }
}
