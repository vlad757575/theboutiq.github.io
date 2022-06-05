<?php

namespace App\Controller\Admin;

use App\Entity\Etat;
use App\Entity\Commande;
use App\Entity\Transporteur;
use App\Controller\Admin\ProduitCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    public function configureCrud(Crud $crud): Crud
    {

        return $crud->setDefaultSort(['id' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add('index', 'detail');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            NumberField::new('getOrderNumber', 'numero de commande')
                ->setFormTypeOption('mapped', false),
            // IdField::new('id'),
            DateTimeField::new('dateCommande', 'date de commande'),
            AssociationField::new('etat', 'etat')
                ->setCrudController(EtatCrudController::class),
            TextField::new('utilisateur.getFullName', 'Client'),
            NumberField::new('transporteurTarif', 'Frais de port'),
            NumberField::new('totalPurchase', 'Montant en €'),
            AssociationField::new('commandeProduits', 'produits')
            // ->setFormTypeOption('mapped', false)
            // ->setCrudController(ProduitCrudController::class),
        ];
    }
}
