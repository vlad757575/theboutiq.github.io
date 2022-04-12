<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="show_panier")
     */
    public function index(): Response
    {
        return $this->render('panier/panier.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }

    /**
     * @Route("/rshow_panier", name="rshow_panier")
     */
    // public function showPanier(Panier $panier): Response
    // {
    //     return $this->render('panier/show.html.twig', [
    //         'panier' => $panier,
    //     ]);
    // }

    /**
     * @Route("/add/{produit}", name="app_add")
     */
    public function add(Produit $produit, SessionInterface $session): Response
    {
        // $session->set("panier", 3);
        // dd($session->get("panier"));

        return new Response('Ajouté' . $produit->getNom());
    }
}
