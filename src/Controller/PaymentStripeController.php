<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Etat;
use App\Entity\Commande;
use Stripe\Checkout\Session;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentStripeController extends AbstractController
{

    /**
     * @Route("commande/commande/recapitulatif/create-session/{token}", name="app_payment_stripe" )
     */
    public function index($token, EntityManagerInterface $entityManager)
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
    public function success(EntityManagerInterface $entityManager, $token, SessionInterface $session, MailerInterface $mailer)
    {
        $commande = $entityManager->getRepository(Commande::class)->findOneBy(array('token' => $token));

        //Je boucle sur les entrées de mon panier
        foreach ($commande->getCommandeProduits()->getValues() as $produit) {

            // Je crée la variable stock dans la quelle je place le stock du produit
            $stock = $produit->getProduit()->getStock();
            // Je recupere le produit dans la bdd
            $prod = $produit->getproduit();
            //Je place la quantité commandée dans la variable quantité
            $quantite = $produit->getQuantite();
            //Je soustrais a qte commandée du stock 
            $prod->setStock($stock - $quantite);
            $entityManager->persist($prod);
        }
        // Je met a jour le stock en bdd
        $entityManager->flush();

        // Vue que le paiement est validé je passe l'id etat 1 => 3
        $etat = $entityManager->getRepository(Etat::class)->find(3);
        // Verification avant envoi
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
        // J'envoi un email de confirmation au client
        $user = $this->getUser();
        $email = (new TemplatedEmail())
            ->from(new Address('contact@theboutiq.fr', 'Theboutiq!'))
            ->to($user->getEmail())
            ->subject('Confirmation de commande')
            ->htmlTemplate('payment/confirmation-email.html.twig');
        // envoi de l'email
        $mailer->send($email);

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
