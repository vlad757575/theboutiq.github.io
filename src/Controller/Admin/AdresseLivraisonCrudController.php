<?php

namespace App\Controller\Admin;

use App\Entity\AdresseLivraison;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AdresseLivraisonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AdresseLivraison::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
