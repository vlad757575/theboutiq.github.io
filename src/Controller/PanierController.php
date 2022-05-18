<?php

namespace App\Controller;

use App\Entity\Panier;
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

        return $this->redirectToRoute('app_produit_index');
    }
    /**
     * @Route("/panier", name="app_panier")
     */
    public function show(SessionInterface $session, ProduitRepository $pr): Response
    {

        $panier = $session->get('panier', []);
        // dd($panier);

        $ids = array_keys($panier);
        $produits = $pr->getAllProduits($ids);

        $tva = 0;
        $total = 0;
        $printablePanier = []; // L'équivalent de l'ancien panier pour l'affichage
        foreach ($panier as $id => $quantite) {
            $produit = $produits[$id];
            $tva += $produit->getMontantHt() * $quantite * $produit->getTva() / 100;
            $total += $produit->getMontantHt() * $quantite;

            $printablePanier[$id] = [
                'quantite' => $quantite,
                'produit' => $produit
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
    public function del(SessionInterface $session): Response
    {
        $session->set('panier', []);


        return $this->redirectToRoute('app_produit_index');
    }

    /**
     * @Route("/plus/{id}", name="add_ligne_panier")
     */
    public function ajout(SessionInterface $session, Produit $produit): Response
    {
        $panier = $session->get('panier', []);
        $id = $produit->getId();

        if (empty($panier[$id])) {
            $panier[$id] = 1;
        } else {
            $panier[$id]++;
        }
        $session->set('panier', $panier);
        // dump($panier);

        return $this->redirectToRoute(
            'app_panier'
            // , [
            // 'produit' => $produit,
            // ]
        );
    }




    /**
     * @Route("/minus/{produit}", name="remove_ligne_panier")
     */
    public function delete(Produit $produit, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $produit = $produit->getId();

        if (!empty($panier[$produit])) {
            if ($panier[$produit] > 1) {
                $panier[$produit]--;
            } else {
                unset($panier[$produit]);
            }
        }
        // On sauvegarde dans la session
        $session->set("panier", $panier);
        return $this->redirectToRoute("app_panier");
    }


    /**
     * @Route("/recapitulatif", name="recapitulatif")
     */
    public function recapitulatif()
    {
        $form = $this->createForm(RecapitulatifType::class, null, [
            'user' => $this->getUser()
        ]);
        return $this->render("panier/recapitulatif.html.twig", [
            'form' => $form->createView()
        ]);
    }
}
