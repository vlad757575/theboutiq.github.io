<?php

namespace App\Form;

use App\Entity\AdresseLivraison;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AdresseLivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];


        $builder
            ->add('nom', TextType::class, [
                'label' => 'Quel nom souhaitez vous donner à votre adresse ?',
                'attr' => [
                    'placeholder' => 'Nommez votre adresse'
                ]
            ])
            ->add('nom_prenom', TextType::class, [
                'label' => 'Quel est votre nom et prenom ?',
                'attr' => [
                    'placeholder' => 'Saisissez votre nom et votre prenom'
                ]
            ])
            ->add('societe', TextType::class, [
                'label' => 'Votre societé',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Facultatif',

                ]

            ])
            ->add('telephone', TelType::class, [
                'label' => 'votre numéro de telephone',
                'attr' => [
                    'placeholder' => 'Saisissez votre numero de telephone'
                ]
            ])
            ->add('numeroRue', TextType::class, [
                'label' => 'Quel est votre numéro de rue',
                'attr' => [
                    'placeholder' => 'Saisissez votre numéro de rue'
                ]

            ])
            ->add('rue', TextType::class, [
                'label' => ' Nom de rue',
                'attr' => ['placeholder' => 'Saisissez le nom de rue']
            ])
            ->add('codepostal', NumberType::class, [
                'label' => 'Votre code postal',
                'attr' => ['placeholder' => 'Saisissez votre code postal']
            ])

            ->add('ville', TextType::class, [
                'label' => 'Votre ville',
                'attr' => ['placeholder' => 'Saisissez votre ville']
            ])
            ->add('pays', CountryType::class, [
                'label' => 'Pays',
                'attr' => [
                    'placeholder' => 'Votre pays'
                ]
            ])
            ->add('infocomplementaire', TextType::class, [
                'label' => 'Information complementaire',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Batiment, interphone, code, etc.',

                ],

            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter mon adresse',
                'attr' => ['class' => 'btn-info']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdresseLivraison::class,
            'user' => array()
        ]);
    }
}
