<?php

namespace App\Controller\Admin;

use App\Entity\AdresseLivraison;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class AdresseLivraisonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AdresseLivraison::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            TextField::new('utilisateur.getFullName'),
            TextField::new('nom'),
            IntegerField::new('numeroRue'),
            TextField::new('rue'),
            IntegerField::new('codepostal'),
            TextField::new('pays'),
            textField::new('telephone'),
            textField::new('societe'),
        ];
    }
}
