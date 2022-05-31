<?php

namespace App\Controller;


use App\Entity\Produit;
use App\Form\RecapitulatifType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\ProduitRepository;
use Stripe\Util\Set;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @IsGranted("ROLE_USER")
 */
class PanierController extends AbstractController
{
    /**
     * @Route("/add/{produit}", name="app_add")
     */
    public function index(Produit $produit, SessionInterface $session, Request $request): Response
    {
        $quantite = $request->request->get('quantite');
        // dd($quantite);

        if ($quantite <= 0) {
            throw new BadRequestHttpException;
        }


        $panier = $session->get('panier', []);

        if (!empty($panier[$produit->getId()])) {
            $panier[$produit->getId()] = min($quantite + $panier[$produit->getId()], $produit->getStock());
        } else {
            $panier[$produit->getId()] = min($quantite, $produit->getStock());
        }
        $session->set("panier", $panier);

        return $this->redirectToRoute('app_produit_index', ['produit' => $produit,]);
    }
    /**
     * @Route("/panier", name="app_panier")
     */
    public function show(SessionInterface $session, ProduitRepository $produit): Response
    {
        // Je recupere mon panier
        $panier = $session->get('panier', []);

        $ids = array_keys($panier);
        //Je recupere tout les produits
        $produits = $produit->getAllProduits($ids);
        //Je definis une valeur de base aux variables tva et total
        $tva = 0;
        $total = 0;
        $printablePanier = []; // L'équivalent de l'ancien panier pour l'affichage

        //Je fais une boucle pour afficher les produits dans le panier
        foreach ($panier as $id => $quantite) {
            $produit = $produits[$id];
            //Je set la tva
            $tva += $produit->getMontantHt() * $quantite * $produit->getTva() / 100;
            $total += $produit->getMontantHt() * $quantite;

            $printablePanier[$id] = [
                'quantite' => $quantite,
                'produit' => $produit,

            ];
        }

        return $this->render(
            'panier/panier.html.twig',
            [
                'produit' => $produit,
                'panier' => $printablePanier,
                'total' => $total,
                'tva' => $tva,
            ],
        );
    }

    /**
     * @Route("/vider-panier", name="vider_panier")
     */
    public function clearAll(SessionInterface $session): Response
    {
        //Je vide le panier
        $session->set('panier', []);


        return $this->redirectToRoute('app_produit_index');
    }

    /**
     * @Route("/plus/{id}", name="add_ligne_panier")
     */
    public function plus(SessionInterface $session, Produit $produit): Response
    {
        // je recupere le panier et les produits
        $panier = $session->get('panier', []);
        $produit = $produit->getId();
        //Si le prduit n'est pas dans le panier j'en ajoute un
        if (empty($panier[$produit])) {
            $panier[$produit] = 1;
        } else {
            // sinon j'incremente
            $panier[$produit]++;
        }
        // Je sauvgarde le panier
        $session->set('panier', $panier);


        return $this->redirectToRoute(
            'app_panier'

        );
    }

    /**
     * @Route("/minus/{produit}", name="remove_ligne_panier")
     */
    public function minus(Produit $produit, SessionInterface $session)
    {
        // Je récupère le panier actuel
        $panier = $session->get("panier", []);
        $produit = $produit->getId();
        //Je verifie si le produit est deja dans le panier et la qtte est superieure a 1 je décremente
        if (!empty($panier[$produit])) {
            if ($panier[$produit] > 1) {
                $panier[$produit]--;
            } else {
                // Sinon je supprime le produit du panier
                unset($panier[$produit]);
            }
        }
        //Je sauvegarde dans la session
        $session->set("panier", $panier);
        return $this->redirectToRoute("app_panier");
    }
    /**
     * @Route("/delete/{id}", name="delign")
     */
    public function deleteLigne(Produit $produit, SessionInterface $session)
    {
        // Je récupère le panier actuel
        $panier = $session->get("panier", []);
        $produit = $produit->getId();
        // je verifie si le panier est vide ou pas
        if (!empty($panier[$produit])) {
            // Si il est pas vide je supprime la ligne du produit en question
            unset($panier[$produit]);
        }

        // Je sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("app_panier");
    }

    /**
     * @Route("/choix", name="choix")
     */
    public function choix(SessionInterface $session, ProduitRepository $produit)
    {


        $panier = $session->get('panier', []);

        $ids = array_keys($panier);
        //Je recupere tout les produits
        $produits = $produit->getAllProduits($ids);
        //Je definis une valeur de base aux variables tva et total
        $tva = 0;
        $total = 0;
        $printablePanier = []; // L'équivalent de l'ancien panier pour l'affichage

        //Je fais une boucle pour afficher les produits dans le panier
        foreach ($panier as $id => $quantite) {
            $produit = $produits[$id];
            //Je set la tva
            $tva += $produit->getMontantHt() * $quantite * $produit->getTva() / 100;
            $total += $produit->getMontantHt() * $quantite;



            $printablePanier[$id] = [
                'quantite' => $quantite,
                'produit' => $produit
            ];
        }
        if (!$this->getUser()->getAdresseLivraison()->getValues()) {
            return $this->redirectToRoute('app_adresse_livraison_new');
        }
        //Je recupere une form
        $form = $this->createForm(RecapitulatifType::class, null, [
            'user' => $this->getUser(),
        ]);



        // J'apelle la vue recapitulatif
        return $this->render("panier/choix.html.twig", [
            'form' => $form->createView(),
            'panier' => $printablePanier,
            'produit' => $produit,


        ]);
    }

    /**
     * @Route("/recapitulatif", name="recapitulatif")
     */
    public function recapitulatif()
    {
        return $this->render('panier/recapitulatif.html.twig');
    }
}
