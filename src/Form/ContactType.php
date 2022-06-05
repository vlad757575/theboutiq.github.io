<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


            ->add('nom', TextType::class,  [
                'constraints' => [
                    new Length([
                        'max' => 50
                    ])
                ]
            ])
            ->add('prenom', TextType::class, [
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
            ->add('objet', TextType::class, [
                'constraints' => [new Length(['max' => 70])]
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
