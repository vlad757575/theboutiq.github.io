<?php

namespace App\Controller;

use Dompdf\Options;
use Dompdf\Dompdf;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/user")
 */
class UtilisateurController extends AbstractController
{

    /**
     * @Route("/index/{id}", name="user")
     */
    public function index($id, UtilisateurRepository $ur): Response
    {

        return $this->render(
            'utilisateur/index.html.twig',
            [
                'utilisateur' => $ur->find($id)
            ]
        );
    }

    /**
     * @Route("/new", name="app_utilisateur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UtilisateurRepository $utilisateurRepository): Response
    {
        // Création d'un nouvel utilisateur
        $utilisateur = new Utilisateur();
        //Il va chercher le fomulaire 
        $form = $this->createForm(UtilisateurType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $utilisateurRepository->add($utilisateur);
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("utilisateur/{id}", name="showUtilisateur", methods={"GET"})
     */
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="app_utilisateur_edit", methods={"GET"})
     */
    public function edit(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository, $id): Response
    {
        $user = $utilisateurRepository->find($id);

        if (!$this->getUser() || !$user || $user != $this->getUser()) {
            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);
        //Tu m'nvoies ca si le formulaire est valide et est soumit 
        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurRepository->add($utilisateur);
            return $this->redirectToRoute('mon_compte', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }


    /**
     * 
     * @Route("/{id}", name="app_utilisateur_delete", methods={"POST"})
     */
    public function delete(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $utilisateur->getId(), $request->request->get('_token'))) {
            $utilisateurRepository->remove($utilisateur);
            //Très important si l'utilisateur a une session en cours
            $session = new Session();
        }

        return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    }

    /**     
     * @Route("/rgpd/{id}/", name="mes-infos", methods={"GET"})
     */
    public function rgpdPdf(UtilisateurRepository $utilisateurRepository, $id, Utilisateur $utilisateur)
    {
        // dd($utilisateur);
        $options = new Options();
        $options->set('defaultFont', 'Calibri');

        $domPdf = new DomPdf($options);

        $domPdf->setOptions($options);
        $domPdf->setPaper('A4', 'protrait');

        $html = $this->renderView('utilisateur/rgpd.html.twig', [
            'utilisateur' => $utilisateurRepository->findOneBy(['id' => $id]),

        ]);

        $domPdf->loadHtml($html);

        $domPdf->render();
        $domPdf->stream("Vos informations");
    }

    /**     
     * @IsGranted("ROLE_ADMIN")
     * @Route("/notverified", name="delete_not_verified")
     */
    public function notVerified(UtilisateurRepository $utilisateurRepository, EntityManagerInterface $entityManager): Response
    {

        // Je vais chercher les utilisateurs non verifiés
        $utilisateurs = $utilisateurRepository->notVerifiedUser();
        // Le temps limite est de 2 heures, je boucle sur les utilisateurs non verifiés et je les supprime de la bdd
        foreach ($utilisateurs as $timelimit) {
            $entityManager->remove($timelimit);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Les utilisateurs non vérifiés ont bien étés supprimés');
        return $this->redirectToRoute('admin');
    }
}
