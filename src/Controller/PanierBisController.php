<?php

namespace App\Controller;

use App\Classe\Panier;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PanierBisController extends AbstractController
{
    // j'ai besoin de l'entity manager pour recuperer les données des produits
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @route("/mon-panier", name="app_mpanier")
     */
    public function index(Panier $panier)
    {
        // Je declare une variable qui va stocker tout mon panier
        $fullPanier = [];
        $tva = 0;
        $total = 0;
        // Je boucle sur mon panier 
        foreach ($panier->get() as $id => $quantite) {

            // $tva += $id->getMontantHt() * $quantite * $id->getTva() / 100;
            // $total += $id->getMontantHt() * $quantite;

            $fullPanier[] = [
                // Je recupere les infos des produits
                'produit' => $this->entityManager->getRepository(Produit::class)->findOneById($id),
                'quantite' => $quantite,
            ];
        }

        return $this->render('panier/panierbis.html.twig', [
            'panier' => $fullPanier,
        ]);
    }

    /**
     * @route("/panier/add/{id}", name="add_panier")
     */
    public function ajout(Panier $panier, $id)
    {
        // j'ajoute un produit grace à son id
        $panier->add($id);

        return $this->redirectToRoute('app_mpanier');
    }

    /**
     * @route("/panier/remove", name="remove_panier")
     */
    public function suppression(Panier $panier)
    {
        // Je vide le panier
        $panier->remove();
        return $this->redirectToRoute('index');
    }

    /**
     * @route("/panier/del/{id}", name="del_ligne-panier")
     */
    public function suppLigne(Panier $panier, $id)
    {
        // Je vide le panier
        $panier->delete($id);
        return $this->redirectToRoute('app_mpanier');
    }
}
