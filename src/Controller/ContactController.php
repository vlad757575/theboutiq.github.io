<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $formulaire = $this->createForm(ContactType::class);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $data = $formulaire->getData();
            // Envoi 
            $email = new TemplatedEmail;
            $email->from('vld.lavrentiev@outlook.fr')
                ->to('vld.lavrentiev@outlook.fr')
                ->replyTo($data['email'])
                ->subject('Vous avez une demande de contact !')
                ->htmlTemplate('contact/email-contact.html.twig')
                ->context([
                    'nom' => $data['nom'],
                    'prenom' => $data['prenom'],
                    'FromEmail' => $data['email'],
                    'objet' => $data['objet'],
                    'message' => $data['message'],

                ]);
            $mailer->send($email);
            $this->addFlash('bravo', 'Le message est envoyé !');
            $formulaire = $this->createForm(ContactType::class);
        }

        return $this->render('contact/index.html.twig', [
            'titre' => 'Contact',
            'form' => $formulaire->createView()
        ]);
    }
}
