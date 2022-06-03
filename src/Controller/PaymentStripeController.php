<?php

namespace App\Controller;

use App\Classe\Panier;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentStripeController extends AbstractController
{
    /**
     * @Route("commande/commande/recapitulatif/create-session/", name="app_payment_stripe")
     */
    public function index(Panier $panier)

    {

        $for_stripe = [];
        $YOUR_DOMAIN = 'http://localhost:3000/public';

        foreach ($panier->getMyPanier() as $produit) {

            $for_stripe[] = [
                // 'mode' => 'payment',
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $produit['produit']->getNom(),
                        'images' => [$YOUR_DOMAIN . "/uploads/" . $produit['produit']->getImage()] // Lien ABSOLU
                    ],
                    'unit_amount' => $produit['produit']->getMontantHt() * 100, // Montant en centimes
                ],
                'quantity' => $produit['quantite'],
            ];
        }

        Stripe::setApiKey($this->getParameter('stripeSecretKey'));

        $checkout_session = Session::create([

            'payment_method_types' => ['card'],
            'line_items' => [
                $for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
            // 'success_url' => $this->generateUrl('payment_success', ['token' => $commande->getToken()], UrlGeneratorInterface::ABSOLUTE_URL),
            // 'cancel_url' => $this->generateUrl('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($checkout_session->url);
    }
}
