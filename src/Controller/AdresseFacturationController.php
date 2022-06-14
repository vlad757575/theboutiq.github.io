<?php

namespace App\Controller;

use App\Entity\AdresseFacturation;
use App\Form\AdresseFacturationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdresseFacturationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/adresse/facturation")
 */
class AdresseFacturationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
        $form = $this->createForm(AdresseFacturationType::class, $adresseFacturation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $adresseFacturation->setUtilisateur($this->getUser());


            $adresseFacturationRepository->add($adresseFacturation);
            $adresseFacturation->setUtilisateur($this->getUser());
            $this->entityManager->persist($adresseFacturation);

            $this->entityManager->flush();


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
        return $this->render('adresse_facturation/show.html.twig', [
            'adresse_facturation' => $adresseFacturation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_adresse_facturation_edit", methods={"GET", "POST"})
     */
    public function edit($id, Request $request, AdresseFacturation $adresseFacturation, AdresseFacturationRepository $adresseFacturationRepository): Response
    {
        $addresse = $this->entityManager->getRepository(AdresseFacturation::class)->findOneById($id);

        if (!$addresse || $addresse->getUtilisateur()  != $this->getUser()) {
            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(AdresseFacturationType::class, $adresseFacturation);
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
