<?php

namespace App\Form;

use App\Entity\AdresseFacturation;
use App\Entity\AdresseLivraison;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecapitulatifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('adresses', EntityType::class, [
                'label' => 'Choisissez votre adresse de livraison',
                'required' => true,
                'class' => AdresseLivraison::class,
                'choices' => $user->getAdresseLivraison(),
                'multiple' => false,
                'expanded' => true

            ])
            ->add('facturation', EntityType::class, [
                'label' => 'Choisissez votre adresse de facturation',
                'required' => true,
                'class' => AdresseFacturation::class,
                'choices' => $user->getAdresseFacturation(),
                'multiple' => false,
                'expanded' => true

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => array()
        ]);
    }
}
