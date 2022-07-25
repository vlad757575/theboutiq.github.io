<?php

namespace App\Controller;

use App\Classe\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

        return $this->render('panier/panierbis.html.twig', [
            'panier' => $panier->getMyPanier(),

        ]);
    }

    /**
     * @route("/panier/add/{id}", name="add_panier")
     */
    public function incremente(Panier $panier, $id)
    {
        // j'ajoute un produit grace à son id
        $panier->add($id);
        return $this->redirectToRoute('app_produit_index');
    }

    /**
     * @route("/panier/plus/{id}", name="incremente_panier")
     */
    public function incrementePanier(Panier $panier, $id)
    {
        // j'ajoute un produit grace à son id
        $panier->add($id);
        return $this->redirectToRoute('app_mpanier');
    }

    /**
     * @route("/panier/decremente/{id}", name="decremente_panier")
     */
    public function decremente(Panier $panier, $id)
    {
        // j'ajoute un produit grace à son id
        $panier->decremente($id);

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
