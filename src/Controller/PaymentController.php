<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Commande;
use Stripe\Checkout\Session;
use App\Entity\CommandeProduit;
use App\Repository\EtatRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Security;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/payment")
 */
class PaymentController extends AbstractController
{
    /**
     * @Route("/", name="paiement")
     */
    public function index(SessionInterface $session, ProduitRepository $prod, CommandeRepository $cr, EtatRepository $er, Security $security): Response
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
        $commande->setDateCommande(new DateTime());

        $utilisateur = $security->getUser();
        $commande->setUtilisateur($utilisateur);


        $line_items = [];

        foreach ($panier as $id => $quantite) {
            $produit = $produits[$id];
            if ($quantite > $produit->getStock()) {
                $this->addFlash('error', 'Le produit n\'est plus en stock ');
                return $this->redirectToRoute('panier');
            }

            // $commande->addProduit($produit);
            $cp = new CommandeProduit;
            $cp->setQuantite($quantite);
            $cp->setProduit($produit);
            $commande->addCommandeProduit($cp);




            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $produit->getNom(),
                        // 'images' => [$produit->getImage()] // Lien ABSOLU
                    ],
                    'unit_amount' => $produit->getMontantTtc() * 100 // Montant en centimes
                ],
                'quantity' => $quantite,
            ];
        }


        $checkout = Session::create([
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('payment_success', ['token' => $commande->getToken()], UrlGeneratorInterface::ABSOLUTE_URL), // Lien ABSOLU
            'cancel_url' => $this->generateUrl('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL), // Lien ABSOLU
        ]);

        $cr->add($commande);

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
        // foreach ($commande as $id => $quantite)
        return $this->render('payment/success.html.twig');
    }

    /**
     * @Route("/cancel", name="payment_cancel")
     */
    public function cancel()
    {
        return $this->render('payment/failed.html.twig');
    }
}
