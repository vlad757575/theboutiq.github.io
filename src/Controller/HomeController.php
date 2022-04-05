<?php

namespace App\Controller;

use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    // /**
    //  * @Route("/signin", name="signin")
    //  */
    // public function signin(): Response
    // {
    //     return $this->render('home/signin.html.twig', [
    //         'controller_name' => 'HomeController',
    //     ]);
    // }

    // /**
    //  * @Route("/forgot-password", name="forgot-password")
    //  */
    // public function forgotpassword(): Response
    // {
    //     return $this->render('home/forgotpassword.html.twig', [
    //         'controller_name' => 'HomeController',
    //     ]);
    // }

    /**
     * @Route("/informations", name="informations")
     */
    public function sendForm(Request $request, MailerInterface $mailer): Response
    {

        $formulaire = $this->createForm(ContactType::class);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $data = $formulaire->getData();

            $email = new TemplatedEmail;
            $email
                ->from('Contact Pronote <' . $data['email'] . '>')
                ->to('2alheure@yopmail.fr')
                ->replyTo($data['email'])
                ->subject('Vous avez une demande de contact.')
                ->htmlTemplate('home/email-informations.html.twig')
                ->context([
                    'fromEmail' => $data['email'],
                    'message' => nl2br($data['message']),
                ]);

            $mailer->send($email);

            $this->addFlash('success', 'Message envoyé.');
        }

        return $this->render('generic/form.html.twig', [
            'titre' => 'Contact',
            'form' => $formulaire->createView()
        ]);
    }
}
