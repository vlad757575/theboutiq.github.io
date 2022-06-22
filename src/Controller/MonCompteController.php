<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use App\Repository\EtatRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;





class MonCompteController extends AbstractController
{
    /**
     * @Route("/mon/compte", name="mon_compte")
     */
    public function index(): Response
    {
        return $this->render('mon_compte/index.html.twig');
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
     * @Route("/mes/infos", name="app_adresses")
     */
    public function adresse(): Response
    {
        return $this->render('mon_compte/adresses.html.twig', [
            'controller_name' => 'MonCompteController',

        ]);
    }

    /**
     * @Route("/mes/retours", name="mes_retours")
     */
    public function mesRetours(EtatRepository $etatRepository, CommandeRepository $commandeRepository): Response
    {
        $etat = $etatRepository->findOneBy(['nom' => 'Retourné']);
        $commandes = $commandeRepository->findBy(['etat' => $etat, 'utilisateur' => $this->getUser()]);
        return $this->render('mon_compte/mes-retours.html.twig', [
            'commandes' => $commandes
        ]);
    }
}
