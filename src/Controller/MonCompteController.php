<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonCompteController extends AbstractController
{
    /**
     * @Route("/mon/compte", name="app_mon_compte")
     */
    public function index(): Response
    {
        return $this->render('mon_compte/index.html.twig', [
            'controller_name' => 'MonCompteController',
        ]);
    }

    /**
     * @Route("/mon/historique", name="app_historique")
     */
    public function historique(): Response
    {
        return $this->render('mon_compte/historique.html.twig', [
            'controller_name' => 'MonCompteController',

        ]);
    }


    /**
     * @Route("/mes/adresses", name="app_adresses")
     */
    public function adresse(): Response
    {
        return $this->render('mon_compte/adresses.html.twig', [
            'controller_name' => 'MonCompteController',

        ]);
    }

    /**
     * @Route("/mes/infos", name="app_infos")
     */
    public function infos(): Response
    {
        return $this->render('mon_compte/mes-infos.html.twig', [
            'controller_name' => 'MonCompteController',

        ]);
    }

    /**
     * @Route("/mes/retours", name="app_retours")
     */
    public function retours(): Response
    {
        return $this->render('mon_compte/mes-retours.html.twig', [
            'controller_name' => 'MonCompteController',

        ]);
    }
}
