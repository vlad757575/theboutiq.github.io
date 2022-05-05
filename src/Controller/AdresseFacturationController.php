<?php

namespace App\Controller;

use App\Entity\AdresseFacturation;
use App\Form\AdresseFacturation1Type;
use App\Repository\AdresseFacturationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/adresse/facturation")
 */
class AdresseFacturationController extends AbstractController
{

    /**
     * @Route("/", name="app_adresse_facturation_index", methods={"GET"})
     */
    public function index(AdresseFacturationRepository $adresseFacturationRepository): Response
    {
        return $this->render('adresse_facturation/index.html.twig', [
            'adresse_facturations' => $adresseFacturationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_adresse_facturation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AdresseFacturationRepository $adresseFacturationRepository): Response
    {
        $adresseFacturation = new AdresseFacturation();
        $form = $this->createForm(AdresseFacturation1Type::class, $adresseFacturation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $adresseFacturation->setUtilisateur($this->getUser());


            $adresseFacturationRepository->add($adresseFacturation);

            return $this->redirectToRoute('app_adresse_facturation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adresse_facturation/new.html.twig', [
            'adresse_facturation' => $adresseFacturation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_adresse_facturation_show", methods={"GET"})
     */
    public function show(AdresseFacturation $adresseFacturation): Response
    {
        return $this->render('mon_compte/adresses.html.twig', [
            'adresse_facturation' => $adresseFacturation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_adresse_facturation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, AdresseFacturation $adresseFacturation, AdresseFacturationRepository $adresseFacturationRepository): Response
    {
        $form = $this->createForm(AdresseFacturation1Type::class, $adresseFacturation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adresseFacturationRepository->add($adresseFacturation);
            return $this->redirectToRoute('app_adresse_facturation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adresse_facturation/edit.html.twig', [
            'adresse_facturation' => $adresseFacturation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_adresse_facturation_delete", methods={"POST"})
     */
    public function delete(Request $request, AdresseFacturation $adresseFacturation, AdresseFacturationRepository $adresseFacturationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $adresseFacturation->getId(), $request->request->get('_token'))) {
            $adresseFacturationRepository->remove($adresseFacturation);
        }

        return $this->redirectToRoute('app_adresse_facturation_index', [], Response::HTTP_SEE_OTHER);
    }
}
