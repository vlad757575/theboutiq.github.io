<?php

namespace App\Controller;


use DateTime;
use App\Entity\Etat;
use App\Classe\Panier;
use App\Entity\Commande;
use App\Form\MyOrderType;
use App\Form\CommandeType;
use App\Entity\CommandeProduit;
use App\Repository\EtatRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),

        ]);
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
    public function choix(Panier $panier, Request $request)
    {
        if (!$this->getUser()->getAdresseLivraison()->getValues()) {

            return $this->redirectToRoute('app_adresse_livraison_new');
        }

        $form = $this->createForm(MyOrderType::class, null, [
            'user' => $this->getUser()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());
        }

        return $this->render('commande/choix.html.twig', [
            'panier' => $panier->getMyPanier(),
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/commande/recapitulatif", name="recapitulatif", methods="POST")
     */
    public function recapitulatif(Panier $panier, Request $request,  EtatRepository $er)
    {
        $form = $this->createForm(MyOrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $dateCommande = new DateTime();
            $transporteur = $form->get('transporteur')->getData();
            $livraison = $form->get('livraisonAdresse')->getData();
            $etat = $er->find(3);



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


            $commande = new Commande();
            $commande->setUtilisateur($this->getUser());
            $commande->setDateCommande($dateCommande);
            $commande->setTransporteurNom($transporteur->getNom());
            $commande->setTransporteurTarif($transporteur->getTarif());
            $commande->setLivraisonAdresse($livraison_info);
            $commande->setToken(hash('sha256', random_bytes(32)));
            $commande->setEtat($etat);

            $this->entityManager->persist($commande);


            foreach ($panier->getMyPanier() as $produit) {
                $commandeProduit = new CommandeProduit;
                $commandeProduit->setCommande($commande);
                $commandeProduit->setMonProduit($produit['produit']->getNom());
                $commandeProduit->setQuantite($produit['quantite']);
                $commandeProduit->setPrix($produit['produit']->getMontantHt());
                $commandeProduit->setTotal($produit['produit']->getMontantHt() * $produit['quantite']);


                $this->entityManager->persist($commandeProduit);

                // dd($commandeProduit);
            }

            $this->entityManager->flush();

            return $this->render('commande/recapitulatif.html.twig', [
                'transporteur' => $transporteur,
                'livraison' => $livraison,
                'panier' => $panier->getMyPanier(),
                'form' => $form->createView(),



            ]);
        }
        return $this->redirectToRoute('app_mpanier');
    }
}
