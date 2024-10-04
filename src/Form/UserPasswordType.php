<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
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
                        'class' => 'form-control mt-4',
                        'placeholder' => 'Nouveau mot de passe',
                    ],
                    'label' => 'Mot de passe',
                    'label_attr' => [
                        'class' => 'd-none'
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
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
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer le même mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
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
