<?php

namespace App\Controller\Admin;

use App\Entity\Etat;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\Utilisateur;
use App\Entity\Transporteur;
use App\Entity\CommandeProduit;
use App\Entity\AdresseLivraison;
use App\Entity\AdresseFacturation;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{

    protected $utilisateurRepository;
    protected $commadeRepository;
    protected $produitRepository;

    public function __construct(
        UtilisateurRepository $utilisateurRepository,
        CommandeRepository $commadeRepository,
        ProduitRepository $produitRepository
    ) {
        $this->utilisateurRepository = $utilisateurRepository;
        $this->commadeRepository = $commadeRepository;
        $this->produitRepository = $produitRepository;
    }



    /**
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(): Response
    {
        return $this->render('bundle/views/welcome.html.twig', [
            'countUser' => $this->utilisateurRepository->countUser(),
            'countOrders' => $this->commadeRepository->countOrders(),
            'countItems' => $this->produitRepository->countItems()
        ]);
    }

    //Menu admin config
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Menu admin Theboutiq!');
    }

    public function menu()
    {

        return $this->renderForm('commande/new.html.twig');
    }


    public function configureMenuItems(): iterable
    {
        // j'ajoute les differentes tables de ma bdd pour acceder au crud
        yield MenuItem::linkToDashboard('Accueil', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateur', 'fa fa-user', Utilisateur::class);
        yield MenuItem::linkToCrud('Produit', 'fas fa-list', Produit::class);
        yield MenuItem::linkToCrud('Commande', 'fas fa-list', Commande::class);
        yield MenuItem::linkToCrud('Livraison', 'fas fa-truck', Transporteur::class);
        yield MenuItem::linkToCrud('Etat', 'fas fa-list', Etat::class);
        yield MenuItem::linkToCrud('Adresse-livraison', 'fas fa-list', AdresseLivraison::class);
        yield MenuItem::linkToCrud('Adresse-facturation', 'fas fa-list', AdresseFacturation::class);
        yield MenuItem::linkToLogout('Logout', 'fas fa-list');
        yield MenuItem::linkToRoute('revenir sur le site', 'fa fa-home', 'index');
    }
}
