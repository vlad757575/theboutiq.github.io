<?php

namespace App\Form;

use App\Entity\AdresseFacturation;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseFacturation1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('numero_rue')
            ->add('rue')
            ->add('codepostal')
            ->add('ville')
            ->add('pays')
            ->add('infocomplementaire');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdresseFacturation::class,
        ]);
    }
}
