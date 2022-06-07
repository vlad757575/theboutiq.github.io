<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Etat;
use App\Classe\Panier;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\Transporteur;
use Doctrine\ORM\Mapping\Id;
use Stripe\Checkout\Session;
use App\Repository\EtatRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Node\RenderBlockNode;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PaymentStripeController extends AbstractController
{
    /**
     * @Route("commande/commande/recapitulatif/create-session/{token}", name="app_payment_stripe" )
     */
    public function index($token, EntityManagerInterface $entityManager, Panier $panier)

    {

        $for_stripe = [];
        $YOUR_DOMAIN = 'http://localhost:3000/public';





        // $commande = $entityManager->getRepository(Commande::class)->findOneBy(['token' => $token]);
        $commande = $entityManager->getRepository(Commande::class)->findOneBy(array('token' => $token));

        // dd($commande->getCommandeProduits()->getValues());
        // dd($commande->getCommandeProduits());

        foreach ($commande->getCommandeProduits()->getValues() as $produit) {
            // $produit_objet = $entityManager->getRepository(Produit::class)->findOneBy($produit->getProduit());
            $for_stripe[] = [
                // 'mode' => 'payment',
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $produit->getPrix() * 100,
                    'product_data' => [
                        'name' => $produit->getMonProduit(),
                        // 'images' => $YOUR_DOMAIN . "/uploads/" . $produit->getImage(),
                    ],
                ],
                'quantity' => $produit->getQuantite(),
            ];
        }




        $for_stripe[] = [
            // 'mode' => 'payment',
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
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('payment_success', ['token' => $commande->getToken()], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' =>  $this->generateUrl('payment_failed', ['token' => $commande->getToken()], UrlGeneratorInterface::ABSOLUTE_URL),
            // 'success_url' => $this->generateUrl('payment_success', ['token' => $commande->getToken()], UrlGeneratorInterface::ABSOLUTE_URL),
            // 'cancel_url' => $this->generateUrl('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);


        return $this->redirect($checkout_session->url);
    }

    /**
     * @Route("commande/commande/recapitulatif/success/{token}", name="payment_success" )
     */
    public function success(EntityManagerInterface $entityManager, $token, SessionInterface $session): Response
    {

        $commande = $entityManager->getRepository(Commande::class)->findOneBy(array('token' => $token));
        $etat = $entityManager->getRepository(Etat::class)->find(3);
        // $etat->$commande->FindOneById(1);

        if (!$commande || $commande->getUtilisateur() != $this->getUser()) {
            return $this->render('index');
        }
        if ($commande->getEtat()->getId() == 1) {

            $commande->setEtat($etat);

            $entityManager->flush();
        }

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
        // $etat->$commande->FindOneById(1);

        if (!$commande || $commande->getUtilisateur() != $this->getUser()) {
            return $this->render('index');
        }

        return $this->render('payment/failed.html.twig', [
            'commande' => $commande,
        ]);
    }


    // /**
    //  * @Route("commande/commande/recapitulatif/shipped/{token}", name="shipped" )
    //  */
    // public function shipped(EntityManagerInterface $entityManager, $id)
    // {

    //     $commande = $entityManager->getRepository(Commande::class)->findOneBy(array('id' => $id));
    //     $etat = $entityManager->getRepository(Etat::class)->find(4);

    //     if (!$commande || $commande->getUtilisateur() != $this->getUser()) {
    //         return $this->render('index');
    //     }

    //     $commande->setEtat($etat);

    //     $entityManager->flush();

    //     return $this->render('index');
    // }
}
