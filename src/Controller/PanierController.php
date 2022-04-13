<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PanierController extends AbstractController
{
    /**
     * @Route("/add/{produit}", name="app_add")
     */
    public function add(Produit $produit, SessionInterface $session, Request $request): Response
    {
        $quantite = $request->request->get('quantite');
        if ($quantite <= 0) throw new BadRequestHttpException;

        // dd($quantite, $produit, $session);
        $panier = $session->get('panier', []);
        if (!empty($panier[$produit->getId()])) $panier[$produit->getId()] = max(0, min($quantite + $panier[$produit->getId()], $produit->getStock()));

        else $panier[$produit->getId()] = [

            'quantite' => min($quantite, $produit->getStock()),
            'produit' => $produit
        ];

        $session->set("panier", $panier);
        return $this->redirectToRoute('app_produit_index');
    }
    /**
     * @Route("/panier", name="panier")
     */
    function show(SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);
        return $this->render('panier/panier.html.twig');
    }
}
