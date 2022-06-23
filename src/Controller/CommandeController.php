<?php

namespace App\Controller;


use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Classe\Panier;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\MyOrderType;
use App\Form\CommandeType;
use App\Entity\CommandeProduit;
use App\Repository\EtatRepository;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/commande")
 */
class CommandeController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="app_commande_index", methods={"GET"})
     */
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render(
            'commande/index.html.twig'
        );
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="app_commande_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CommandeRepository $commandeRepository): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->add($commande);
            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_commande_show", methods={"GET"})
     */
    public function show(Commande $commande): Response
    {

        if (!$commande || $commande->getUtilisateur() != $this->getUser()) {
            return $this->render('commande/show.html.twig');
        }
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}/edit", name="app_commande_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {

        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->add($commande);
            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="app_commande_delete", methods={"POST"})
     */
    public function delete(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commande->getId(), $request->request->get('_token'))) {
            $commandeRepository->remove($commande);
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/commande/choix", name="choix")
     */
    public function choix(Panier $panier, Request $request): Response
    {
        if (!$this->getUser()->isVerified()) {

            return $this->redirectToRoute('index');
        }
        if (!$this->getUser()->getAdresseLivraison()->getValues()) {

            return $this->redirectToRoute('app_adresse_livraison_new');
        }

        $form = $this->createForm(MyOrderType::class, null, [
            'user' => $this->getUser()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        }

        return $this->render('commande/choix.html.twig', [
            'panier' => $panier->getMyPanier(),
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/commande/recapitulatif", name="recapitulatif", methods="POST")
     */
    public function recapitulatif(Panier $panier, Request $request,  EtatRepository $er, ProduitRepository $produitRepository)
    {
        $form = $this->createForm(MyOrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $dateCommande = new DateTime();
            $transporteur = $form->get('transporteur')->getData();
            $livraison = $form->get('livraisonAdresse')->getData();
            $facturation = $form->get('facturationAdresse')->getData();
            $etat = $er->find(1);


            // Je recupere les informations liés à la livraison
            $livraison_info = $livraison->getNomPrenom();
            $livraison_info .= '<br/>' . $livraison->getTelephone();

            if ($livraison->getSociete()) {

                $livraison_info .= '<br/>' . $livraison->getSociete();
            }
            $livraison_info .= '<br/>' . $livraison->getNumeroRue();
            $livraison_info .= '<br/>' .  $livraison->getRue();

            if ($livraison->getInfoComplementaire()) {

                $livraison_info .= '<br/>' . $livraison->getInfoComplementaire();
            }

            $livraison_info .= '<br/>' . $livraison->getCodepostal();
            $livraison_info .= '<br/>' . $livraison->getVille();
            $livraison_info .= '<br/>' . $livraison->getPays();

            //Je recupere toutes les information liés à la facturation

            $facturation_info = $livraison->getNomPrenom();
            $facturation_info .= '<br/>' . $facturation->getTelephone();

            if ($facturation->getSociete()) {
                $facturation_info .= '<br/>' . $facturation->getSociete();
            }
            $facturation_info .= '<br/>' . $facturation->getNumeroRue();
            $facturation_info .= '<br/>' .  $facturation->getRue();

            if ($facturation->getInfoComplementaire()) {

                $facturation_info .= '<br/>' . $facturation->getInfoComplementaire();
            }
            $facturation_info .= '<br/>' . $facturation->getCodepostal();
            $facturation_info .= '<br/>' . $facturation->getVille();
            $facturation_info .= '<br/>' . $facturation->getPays();


            $commande = new Commande();
            $commande->setUtilisateur($this->getUser());
            $commande->setDateCommande($dateCommande);
            $commande->setTransporteurNom($transporteur->getNom());
            $commande->setTransporteurTarif($transporteur->getTarif());
            $commande->setLivraisonAdresse($livraison_info);
            $commande->setFacturationAdresse($facturation_info);
            $commande->setToken(hash('sha256', random_bytes(32)));
            $commande->setEtat($etat);

            //Je fige les données de commande
            $this->entityManager->persist($commande);

            foreach ($panier->getMyPanier() as $produit) {
                $commandeProduit = new CommandeProduit;
                $commandeProduit->setCommande($commande);
                $commandeProduit->setMonProduit($produit['produit']->getNom());
                $commandeProduit->setQuantite($produit['quantite']);
                $commandeProduit->setPrix($produit['produit']->getMontant());
                $commandeProduit->setTotal($produit['produit']->getMontant() * $produit['quantite']);



                // je fige les données de commandeProduit
                $this->entityManager->persist($commandeProduit);
            }
            // $produit = $produitRepository->find();
            // foreach ($panier as $id => $quantite) {
            //     $stock = $produit->getNom();
            //     dd($stock);

            //     $produit->setStock($stock - $produit['quantite']);

            // J'envoi tout en bdd
            $this->entityManager->flush();

            return $this->render('commande/recapitulatif.html.twig', [
                'transporteur' => $transporteur,
                'livraison' => $livraison,
                'panier' => $panier->getMyPanier(),
                'form' => $form->createView(),
                'commande' => $commande,
            ]);
        }
        return $this->redirectToRoute('app_mpanier');
    }

    /**     
     * @Route("/commande/generatePdf/{id}", name="facture", methods={"GET"})
     */
    public function getPdf(CommandeRepository $commandeRepository, $id, Request $request)
    {
        //j'instancie les options et parametres
        $options = new Options();
        $options->set('defaultFont', 'Calibri');
        //j'instancie un nouvel objet DomPdf
        $domPdf = new DomPdf($options);

        $domPdf->setOptions($options);
        $domPdf->setPaper('A4', 'portrait');

        $html = $this->renderView('commande/telechargement.html.twig', [
            'facture' => $commandeRepository->findOneBy(['id' => $id]),

        ]);
        //j'injecte ma vue html
        $domPdf->loadHtml($html);
        //Je crée le pdf
        // $domPdf->output();
        $domPdf->setBasePath($request->getSchemeAndHttpHost());
        $domPdf->render();
        // Je lui donne un nom
        $domPdf->stream("Votre facture - theboutiq!");
    }
}
