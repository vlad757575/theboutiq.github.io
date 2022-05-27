<?php

namespace App\Controller\Admin;

use App\Entity\Etat;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EtatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Etat::class;
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
