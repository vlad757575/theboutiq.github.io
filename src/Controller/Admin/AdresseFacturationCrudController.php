<?php

namespace App\Controller\Admin;

use App\Entity\AdresseFacturation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AdresseFacturationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AdresseFacturation::class;
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
