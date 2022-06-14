<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Etat;
use App\Classe\Panier;
use App\Entity\Commande;
use App\Entity\Utilisateur;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PaymentStripeController extends AbstractController
{

    // private $entityManager;

    // public function __construct(EntityManagerInterface $entityManager)
    // {
    //     $this->entityManager->$entityManager;
    // }

    /**
     * @Route("commande/commande/recapitulatif/create-session/{token}", name="app_payment_stripe" )
     */
    public function index($token, EntityManagerInterface $entityManager, Panier $panier)
    {

        $for_stripe = [];
        $YOUR_DOMAIN = 'http://localhost:3000/public';

        $commande = $entityManager->getRepository(Commande::class)->findOneBy(array('token' => $token));


        //Je boucle sur les entrées de mon panier
        foreach ($commande->getCommandeProduits()->getValues() as $produit) {
            // Je transmet la quantité et le prix des produits à stripe
            $for_stripe[] = [

                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $produit->getPrix() * 100,
                    'product_data' => [
                        'name' => $produit->getMonProduit(),
                    ],
                ],
                'quantity' => $produit->getQuantite(),
            ];
        }
        // Je transmet le nom et le prix de la livraison
        $for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $commande->getTransporteurTarif() * 100,
                'product_data' => [
                    'name' => $commande->getTransporteurNom(),
                ],
            ],
            'quantity' => 1,
        ];

        Stripe::setApiKey($this->getParameter('stripeSecretKey'));

        $checkout_session = Session::create([
            // Je preremplis le champ email de l'utilisateur
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $for_stripe
            ],
            'mode' => 'payment',
            // Les redirections en cas de echec ou success de paiement
            'success_url' => $this->generateUrl('payment_success', ['token' => $commande->getToken()], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' =>  $this->generateUrl('payment_failed', ['token' => $commande->getToken()], UrlGeneratorInterface::ABSOLUTE_URL),

        ]);


        return $this->redirect($checkout_session->url);
    }

    /**
     * @Route("commande/commande/recapitulatif/success/{token}", name="payment_success" )
     */
    public function success(EntityManagerInterface $entityManager, $token, SessionInterface $session): Response
    {

        $commande = $entityManager->getRepository(Commande::class)->findOneBy(array('token' => $token));
        // dd($commande);
        // $customerEmail = $entityManager->getRepository(Utilisateur::class)->findOneBy(array('email' => $email));
        // Vue que le paiement est validé je passe l'id etat 1 => 3
        $etat = $entityManager->getRepository(Etat::class)->find(3);

        if (!$commande || $commande->getUtilisateur() != $this->getUser()) {
            return $this->render('index');
        }
        if ($commande->getEtat()->getId() == 1) {

            $commande->setEtat($etat);
            //J'envoie en bdd
            $entityManager->flush();
        }
        // Je vide le panier
        $session->set('panier', []);

        return $this->render('payment/success.html.twig',  [
            'commande' => $commande,
        ]);
    }

    /**
     * @Route("commande/commande/recapitulatif/failed/{token}", name="payment_failed" )
     */
    public function failed($token, EntityManagerInterface $entityManager): Response
    {

        $commande = $entityManager->getRepository(Commande::class)->findOneBy(array('token' => $token));
        $etat = $entityManager->getRepository(Etat::class)->find(1);

        if (!$commande || $commande->getUtilisateur() != $this->getUser()) {
            return $this->render('index');
        }
        //PS: Le panier n'est pas vide 
        return $this->render('payment/failed.html.twig', [
            'commande' => $commande,
        ]);
    }
}
