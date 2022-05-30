<?php

namespace App\Controller;

use App\Classe\Panier;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierBisController extends AbstractController
{
    /**
     * @route("/mon-panier", name="app_mpanier")
     */
    public function index(Panier $panier)
    {
        return $this->render('panier/panierbis.html.twig');
    }

    /**
     * @route("/panier/add/{id}", name="add_panier")
     */
    public function ajout(Panier $panier, $id)
    {
        $panier->add($id);

        return $this->redirectToRoute('app_mpanier');
    }

    /**
     * @route("/panier/remove/{id}", name="remove_panier")
     */
    public function suppression(Panier $panier)
    {
        $panier->remove();
        return $this->redirectToRoute('home');
    }
}
