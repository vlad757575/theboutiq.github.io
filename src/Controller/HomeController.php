<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig');
    }

    /**
     * @Route("/mentions-legales", name="app_mentions")
     */
    public function mentions(): Response
    {
        return $this->render('home/mentions-legales.html.twig');
    }

    /**
     * @Route("/cguv", name="app_cguv")
     */
    public function cgv(): Response
    {
        return $this->render('home/cguv.html.twig');
    }


    /**
     * 
     * @Route("/faq", name="app_faq")
     */
    public function faq(): Response
    {
        return $this->render('home/faq.html.twig');
    }
}
