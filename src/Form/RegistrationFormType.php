<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('nom', TextType::class, [
                // 'mapped' => false,
                'required' => true,
            ])
            ->add('prenom', TextType::class, [
                // 'mapped' => false,
                'required' => true,

            ])
            ->add('dateNaissance', DateType::class, [
                // 'mapped' => false,
                'required' => true,
                'widget' => 'single_text'
            ])

            ->add('telephone', TelType::class, [
                // 'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank,
                    new Length([
                        'min' => 6,
                        'max' => 4096,
                    ]),
                ]
            ])

            ->add('email', EmailType::class)


            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'label' => 'Mot de passe',
                'label' => 'Votre mot de passe',
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'mapped' => false,
                'required' => true,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmez votre mot de passe'],
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank,
                    new Length([
                        'min' => 6,
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'message' => 'Votre mot de passe doit contenir au moins un chiffre.'
                    ]),
                    new Regex([
                        'pattern' => '/[a-z]/',
                        'message' => 'Votre mot de passe doit contenir au moins une lettre minuscule.'
                    ]),
                    new Regex([
                        'pattern' => '/[A-Z]/',
                        'message' => 'Votre mot de passe doit contenir au moins une lettre majuscule.'
                    ]),
                    new Regex([
                        'pattern' => '/[!@#$%^&*]/',
                        'message' => 'Votre mot de passe doit contenir au moins un caractère spécial.'
                    ]),

                ],

            ])
            // ->add('passwordConfirm', PasswordType::class, [
            //     'mapped' => false,
            //     'label' => 'confirmez votre mot de passe',
            //     'attr' => ['placeholder' => 'Confirmez votre mot de passe'],
            // ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions générales.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
