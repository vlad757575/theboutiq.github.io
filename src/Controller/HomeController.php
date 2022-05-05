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

    /**
     * @Route("/mentions-legales", name="app_mentions")
     */
    public function mentions(): Response
    {
        return $this->render('home/mentions-legales.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/cguv", name="app_cguv")
     */
    public function cgv(): Response
    {
        return $this->render('home/cguv.html.twig', [
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
}
