<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\Session\SessionInterface;



class Panier
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function add($id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        $this->session->set('panier', $panier);
    }

    public function get()
    {
        return $this->session->get('panier');
    }

    public function remove()
    {
        return $this->session->remove('panier');
    }

    public function delete($id)
    {
        $panier = $this->session->get('panier', []);
        unset($panier[$id]);
        return $panier;
    }
}
