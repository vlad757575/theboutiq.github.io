<?php

namespace App\Classe;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;



class Panier
{

    private $session;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function add($id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        $this->session->set('panier', $panier);
    }

    public function get()
    {


        return $this->session->get('panier');
    }

    public function remove()
    {
        return $this->session->remove('panier');
    }

    public function delete($id)
    {
        $panier = $this->session->get('panier', []);

        unset($panier[$id]);

        return $this->session->set('panier', $panier);
    }

    public function decremente($id)
    {
        $panier = $this->session->get('panier', []);

        if ($panier[$id] > 1) {
            $panier[$id]--;
        } else {
            unset($panier[$id]);
        }
        return $this->session->set('panier', $panier);
    }

    public function getMyPanier()
    {
        $fullPanier = [];

        if ($this->get()) {
            // Je boucle sur les produits ajoutées dans le panier dans le but de les afficher
            foreach ($this->get() as $id => $quantite) {
                $produit_class = $this->entityManager->getRepository(Produit::class)->findOneById($id);
                // Par mesure de securité si l'id demandé n'existe pas ca supprime le produit inexistant du panier
                if (!$produit_class) {
                    $this->delete($id);
                    // On peut ajouter les produits existants
                    continue;
                }
                $fullPanier[] = [
                    // Je recupere les infos des produits
                    'produit' => $produit_class,
                    'quantite' => $quantite,
                ];
            }
        }
        return $fullPanier;
    }
}
