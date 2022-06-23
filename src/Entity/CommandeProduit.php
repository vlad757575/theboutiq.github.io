<?php

namespace App\Entity;

use App\Repository\CommandeProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeProduitRepository::class)
 */
class CommandeProduit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="commandeProduits")
     */
    private $commande;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $monProduit;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="commandeProduits")
     */
    private $produit;

    // /**
    //  * @ORM\ManyToOne(targetEntity=Produit::class)
    //  * @ORM\JoinColumn(nullable=true)
    //  */
    // private $product;



    public function __construct()
    {
        $this->product = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->getMonProduit() . ' X ' . $this->getQuantite();
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }


    public function getMonProduit(): ?string
    {
        return $this->monProduit;
    }

    public function setMonProduit(string $monProduit): self
    {
        $this->monProduit = $monProduit;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    // public function getProduct(): ?Produit
    // {
    //     return $this->product;
    // }

    // public function setProduct(?Produit $product): self
    // {
    //     $this->product = $product;

    //     return $this;
    // }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }
}
