<?php

namespace App\Form;

use App\Entity\AdresseLivraison;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseLivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];


        $builder
            ->add('nom')
            ->add('numeroRue')
            ->add('rue')
            ->add('codepostal', NumberType::class)
            ->add('ville')
            ->add('pays', CountryType::class, [
                'label' => 'Pays',
                'attr' => [
                    'placeholder' => 'Votre pays'
                ]
            ])
            ->add('infocomplementaire')
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter mon adresse',
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
