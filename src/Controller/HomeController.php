<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="index")
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
     * @Route("/signin", name="signin")
     */
    public function signin(): Response
    {
        return $this->render('home/signin.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/forgot-password", name="forgot-password")
     */
    public function forgotpassword(): Response
    {
        return $this->render('home/forgotpassword.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
