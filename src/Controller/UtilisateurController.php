<?php

namespace App\Controller;

use App\Entity\Commande;
use Dompdf\Options;
use Dompdf\Dompdf;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\CommandeRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * * @IsGranted("ROLE_USER")
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
        $utilisateur = new Utilisateur();
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
     * @Route("/{id}/edit", name="app_utilisateur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

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
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="app_utilisateur_delete", methods={"POST"})
     */
    public function delete(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $utilisateur->getId(), $request->request->get('_token'))) {
            $utilisateurRepository->remove($utilisateur);
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
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
            // 'commande' => $utilisateur->getAdresseLivraison(),


        ]);

        $domPdf->loadHtml($html);

        $domPdf->render();
        $domPdf->stream("Vos informations");
    }
}
