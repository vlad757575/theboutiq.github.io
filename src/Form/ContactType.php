<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('nom', TextareaType::class, [
                'constraints' => [
                    new Length([
                        'max' => 50
                    ])
                ]
            ])
            ->add('prenom', TextareaType::class, [
                'constraints' => [
                    new Length([
                        'max' => 50
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email
                ]
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new Length([
                        'max' => 2000
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
