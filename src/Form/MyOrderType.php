<?php

namespace App\Form;

use App\Entity\AdresseFacturation;
use App\Entity\Transporteur;
use App\Entity\AdresseLivraison;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MyOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $user = $options['user'];

        $builder
            ->add('livraisonAdresse', EntityType::class, [
                'label' => 'Choisissez votre adresse de livraison',
                'required' => true,
                'class' => AdresseLivraison::class,
                'choices' => $user->getAdresseLivraison(),
                'multiple' => false,
                'expanded' => true
            ])
            ->add('facturationAdresse', EntityType::class, [
                'label' => 'Choisissez votre adresse de facturation',
                'required' => true,
                'class' => AdresseFacturation::class,
                'choices' => $user->getAdresseFacturation(),
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'label_html' => 'br'
                ]
            ])
            ->add('transporteur', EntityType::class, [
                'label' => 'Choisissez votre livraison',
                'required' => true,
                'class' => Transporteur::class,
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider ma commande',
                'attr' => [
                    'class' => 'btn btn-primary m-3',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => array(),
        ]);
    }
}
