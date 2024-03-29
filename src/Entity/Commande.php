<?php

namespace App\Entity;

use App\Entity\Produit;
use App\Entity\Utilisateur;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCommande;


    /**
     * @ORM\ManyToOne(targetEntity=Etat::class, inversedBy="commandes")
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="commande")
     * @ORM\JoinColumn(name="utilisateur_id", onDelete="SET NULL", nullable=true)
     */
    private $utilisateur;

    /**
     * @ORM\OneToMany(targetEntity=CommandeProduit::class, mappedBy="commande", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $commandeProduits;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $transporteurNom;

    /**
     * @ORM\Column(type="float")
     */
    private $transporteurTarif;

    /**
     * @ORM\Column(type="text")
     */
    private $livraisonAdresse;

    /**
     * @ORM\Column(type="text")
     */
    private $facturationAdresse;



    public function __construct()
    {
        $this->commandeProduits = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }



    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function __toString()
    {
        return $this->getDateCommande()->format('Ymd');
    }


    public function getOrderNumber()
    {
        $date = $this->getDateCommande();
        $numberOrder = $this->getDateCommande()->format('Ymd') . '' . $this->getId();
        return $numberOrder;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }





    public function totalPurchase()
    {

        $total = null;


        foreach ($this->getCommandeProduits()->getValues() as $commandeProduits) {
            $total = $total + ($commandeProduits->getPrix() * $commandeProduits->getQuantite());
        }

        return $total;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return Collection<int, CommandeProduit>
     */
    public function getCommandeProduits(): Collection
    {
        return $this->commandeProduits;
    }

    public function addCommandeProduit(CommandeProduit $commandeProduit): self
    {
        if (!$this->commandeProduits->contains($commandeProduit)) {
            $this->commandeProduits[] = $commandeProduit;
            $commandeProduit->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeProduit(CommandeProduit $commandeProduit): self
    {
        if ($this->commandeProduits->removeElement($commandeProduit)) {
            // set the owning side to null (unless already changed)
            if ($commandeProduit->getCommande() === $this) {
                $commandeProduit->setCommande(null);
            }
        }

        return $this;
    }



    public function getTransporteurNom(): ?string
    {
        return $this->transporteurNom;
    }

    public function setTransporteurNom(string $transporteurNom): self
    {
        $this->transporteurNom = $transporteurNom;

        return $this;
    }

    public function getTransporteurTarif(): ?float
    {
        return $this->transporteurTarif;
    }

    public function setTransporteurTarif(float $transporteurTarif): self
    {
        $this->transporteurTarif = $transporteurTarif;

        return $this;
    }

    public function getLivraisonAdresse(): ?string
    {
        return $this->livraisonAdresse;
    }

    public function setLivraisonAdresse(string $livraisonAdresse): self
    {
        $this->livraisonAdresse = $livraisonAdresse;

        return $this;
    }

    public function getFacturationAdresse(): ?string
    {
        return $this->facturationAdresse;
    }

    public function setFacturationAdresse(string $facturationAdresse): self
    {
        $this->facturationAdresse = $facturationAdresse;

        return $this;
    }
}
