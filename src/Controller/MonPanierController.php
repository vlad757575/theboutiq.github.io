<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonPanierController extends AbstractController
{
    /**
     * @Route("/mon/panier", name="app_mon_panier")
     */
    public function index(): Response
    {
        return $this->render('mon_panier/index.html.twig', [
            'controller_name' => 'MonPanierController',
        ]);
    }
}
