<?php

namespace App\Controller\Admin;

use App\Entity\Transporteur;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class TransporteurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Transporteur::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('nom'),
            NumberField::new('tarif'),
            TextEditorField::new('description'),
        ];
    }
}
