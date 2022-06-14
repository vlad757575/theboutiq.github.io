<?php

namespace App\Controller;

use App\Classe\Panier;
use App\Entity\Utilisateur;
use App\Entity\AdresseLivraison;
use App\Form\AdresseLivraisonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AdresseLivraisonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/adresse/livraison")
 */
class AdresseLivraisonController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="app_adresse_livraison_index", methods={"GET"})
     */
    public function index(AdresseLivraisonRepository $adresseLivraisonRepository): Response
    {
        // dd($this->getUser());
        return $this->render('adresse_livraison/index.html.twig', [
            'adresse_livraisons' => $adresseLivraisonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_adresse_livraison_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AdresseLivraisonRepository $adresseLivraisonRepository, SessionInterface $session, Panier $panier): Response
    {


        $adresseLivraison = new AdresseLivraison();
        $form = $this->createForm(AdresseLivraisonType::class, $adresseLivraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adresseLivraisonRepository->add($adresseLivraison);
            $adresseLivraison->setUtilisateur($this->getUser());
            $this->entityManager->persist($adresseLivraison);
            $this->entityManager->flush();

            if ($panier->get()) {

                return $this->redirectToRoute('choix');
            } else {

                return $this->redirectToRoute('app_adresse_livraison_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('adresse_livraison/new.html.twig', [
            'adresse_livraison' => $adresseLivraison,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_adresse_livraison_show", methods={"GET"})
     */
    public function show(AdresseLivraison $adresseLivraison): Response
    {
        return $this->render('adresse_livraison/show.html.twig', [
            'adresse_livraison' => $adresseLivraison,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_adresse_livraison_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, AdresseLivraison $adresseLivraison, AdresseLivraisonRepository $adresseLivraisonRepository, $id): Response
    {
        $adresse = $this->entityManager->getRepository(AdresseLivraison::class)->findOneById($id);

        if (!$adresse || $adresse->getUtilisateur()  != $this->getUser()) {
            return $this->redirectToRoute('index');
        }


        $form = $this->createForm(AdresseLivraisonType::class, $adresseLivraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adresseLivraisonRepository->add($adresseLivraison);
            return $this->redirectToRoute('app_adresse_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adresse_livraison/edit.html.twig', [
            'adresse_livraison' => $adresseLivraison,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_adresse_livraison_delete", methods={"POST"})
     */
    public function delete(Request $request, AdresseLivraison $adresseLivraison, AdresseLivraisonRepository $adresseLivraisonRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $adresseLivraison->getId(), $request->request->get('_token'))) {
            $adresseLivraisonRepository->remove($adresseLivraison);
        }

        return $this->redirectToRoute('app_adresse_livraison_index', [], Response::HTTP_SEE_OTHER);
    }
}
