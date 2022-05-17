<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    //Menu admin config
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Menu admin Theboutiq!');
    }

    // public function menu()
    // {

    //     return $this->renderForm('commande/new.html.twig');
    // }


    public function configureMenuItems(): iterable
    {
        // j'ajoute les differentes tables de ma bdd pour acceder au crud
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-list', Utilisateur::class);
        yield MenuItem::linkToCrud('Produit', 'fas fa-list', Produit::class);
        yield MenuItem::linkToCrud('Commande', 'fas fa-list', Commande::class);
    }
}
