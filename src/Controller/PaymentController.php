<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Commande;
use App\Entity\Etat;
use Stripe\Checkout\Session;
use App\Repository\EtatRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;



/**
 * @IsGranted("ROLE_USER")
 * @Route("/payment")
 */
class PaymentController extends AbstractController
{
    /**
     * @Route("/", name="paiement")
     */
    public function index(SessionInterface $session, ProduitRepository $prod, CommandeRepository $cr, EtatRepository $er): Response
    {

        $panier = $session->get('panier');

        Stripe::setApiKey($this->getParameter('stripeSecretKey'));

        if (empty($panier)) {
            $this->addFlash('error', 'Votre panier est vide, ajoutez un produit dans votre panier puis réessayez ');
            return $this->redirectToRoute('app_produit_index');
        }

        $ids = array_keys($panier);
        $produits = $prod->getAllProduits($ids);


        $etat = $er->find(1);


        $commande = new Commande;
        $commande->setEtat($etat);
        $commande->setToken(hash('sha256', random_bytes(32)));
        $line_items = [];

        foreach ($panier as $id => $quantite) {
            $produit = $produits[$id];
            $commande->addProduit($produit);


            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $produit->getNom(),
                        'images' => [$produit->getImage()] // Lien ABSOLU
                    ],
                    'unit_amount' => $produit->getMontantHt() * 100 // Montant en centimes
                ],
                'quantity' => $quantite,
            ];
        }

        $cr->add($commande);
        $checkout = Session::create([
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('payment_success', ['token' => $commande->getToken()], UrlGeneratorInterface::ABSOLUTE_URL), // Lien ABSOLU
            'cancel_url' => $this->generateUrl('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL), // Lien ABSOLU
        ]);

        return $this->redirect($checkout->url);
    }

    /**
     * @Route("/success/{token}", name="payment_success")
     */

    public function success($token, SessionInterface $session, CommandeRepository $cr, EtatRepository $er): Response
    {
        $commande = $cr->findOneBy([
            'token' => $token
        ]);

        if (empty($commande)) throw new AccessDeniedHttpException;

        $etat = $er->find(2);


        $session->set('panier', []);
        $commande->setEtat($etat);
        $cr->add($commande);

        return $this->render('payment/success.html.twig');
    }

    /**
     * @Route("/cancel", name="payment_cancel")
     */
    public function cancel()
    {
        return $this->render('payment/cancel.html.twig');
    }
}
