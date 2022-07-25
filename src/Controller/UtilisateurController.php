<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/user")
 */
class UtilisateurController extends AbstractController
{

    /**
     * @Route("/index/{id}", name="user")
     */
    public function index($id, UtilisateurRepository $utilisateurRepository): Response
    {
        $user = $utilisateurRepository->find($id);
        if (!$this->getUser() || !$user || $user != $this->getUser()) {

            //je crée une nouvelle session
            $session = new Session();

            // Je ferme la session de l'utilisateur
            $session->invalidate();
            return $this->redirectToRoute('login');
        }
        return $this->render(
            'utilisateur/index.html.twig',
            [
                'utilisateur' => $utilisateurRepository->find($id)
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
    public function show(Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository, $id): Response
    {

        // Je déclare la variable user et je place l'utilisateur dedans
        $user = $utilisateurRepository->find($id);
        // Verification que c'est le meme utilisateur, si c'est pas le meme redirection à l'accueil
        if (!$this->getUser() || !$user || $user != $this->getUser()) {

            //je crée une nouvelle session
            $session = new Session();

            // Je ferme la session de l'utilisateur
            $session->invalidate();
            return $this->redirectToRoute('login');
        }
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }


    /**
     * 
     * @Route("/{id}/edit", name="app_utilisateur_edit")
     */
    public function edit(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository, $id): Response
    {

        // Je déclare la variable user et je place l'utilisateur dedans
        $user = $utilisateurRepository->find($id);
        // Verification que c'est le meme utilisateur, si c'est pas le meme redirection à l'accueil
        if (!$this->getUser() || !$user || $user != $this->getUser()) {

            //je crée une nouvelle session
            $session = new Session();

            // Je ferme la session de l'utilisateur
            $session->invalidate();
            return $this->redirectToRoute('login');
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
    public function delete(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository, MailerInterface $mailer): Response
    {
        if ($this->isCsrfTokenValid('delete' . $utilisateur->getId(), $request->request->get('_token'))) {
            // Je recupere le l'utilisateur actuel
            $utilisateur = $this->getUser();
            $email = (new TemplatedEmail())
                ->from(new Address('contact@theboutiq.fr', 'Theboutiq!'))
                ->to($utilisateur->getEmail())
                ->subject('Confirmation de suppression de votre compte')
                ->htmlTemplate('utilisateur/confirmation-suppression.html.twig');
            // envoi de l'email
            $mailer->send($email);

            $utilisateurRepository->remove($utilisateur);

            //je crée une nouvelle session
            $session = new Session();

            // Je ferme la session de l'utilisateur
            $session->invalidate();
        }

        return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    }

    /**     
     * @Route("/rgpd/{id}", name="mes-infos", methods={"GET"})
     */
    public function rgpdPdf(UtilisateurRepository $utilisateurRepository, $id, Request $request)
    {

        $options = new Options();
        $options->set('defaultFont', 'Calibri');

        $domPdf = new DomPdf($options);

        $domPdf->setOptions($options);
        $domPdf->setPaper('A4', 'protrait');
        $html = $this->renderView('utilisateur/rgpd.html.twig', [
            'utilisateur' => $utilisateurRepository->findOneBy(['id' => $id]),

        ]);

        $domPdf->loadHtml($html);
        $domPdf->setBasePath($request->getSchemeAndHttpHost());
        $domPdf->render();
        $domPdf->stream(
            "Vos informations - theboutiq!"
        );
    }

    /**     
     * @IsGranted("ROLE_ADMIN")
     * @Route("/notverified", name="delete_not_verified")
     */
    public function notVerified(UtilisateurRepository $utilisateurRepository, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {

        // Je vais chercher les utilisateurs non verifiés
        $utilisateurs = $utilisateurRepository->notVerifiedUser();
        // Le temps limite est de 5 heures, je boucle sur les utilisateurs non verifiés et je les supprime de la bdd
        foreach ($utilisateurs as $timelimit) {
            $entityManager->remove($timelimit);

            // J'envoi un email à l'utilisateur pour l'informer de la suppression de son compte

            $utilisateur = $this->getUser();
            $email = (new TemplatedEmail())
                ->from(new Address('contact@theboutiq.fr', 'Theboutiq!'))
                ->to($utilisateur->getEmail())
                ->subject('Suppression de votre compte')
                ->htmlTemplate('utilisateur/confirmation-suppression-dead-line.html.twig');
            // envoi de l'email
            $mailer->send($email);

            $entityManager->flush();
        }
        $this->addFlash('success', 'Les utilisateurs non vérifiés ont bien étés supprimés');
        return $this->redirectToRoute('admin');
    }
}
