<?php

namespace App\Controller\Admin;

use App\Entity\AdresseFacturation;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class AdresseFacturationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AdresseFacturation::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('utilisateur.getFullName'),
            TextField::new('nom'),
            IntegerField::new('numero_rue'),
            TextField::new('rue'),
            IntegerField::new('codepostal'),
            TextField::new('ville'),
            TextField::new('infocomplementaire'),
            TextField::new('societe'),
            TextField::new('telephone'),
            IntegerField::new('siret'),
            TextField::new('num_tva'),
        ];
    }
}
