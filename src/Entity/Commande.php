<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="integer")
     */
    private $quantiteCommande;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCommande;

    /**
     * @ORM\Column(type="float")
     */
    private $montantHt;

    /**
     * @ORM\Column(type="float")
     */
    private $montantTtc;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $transporteur;

    /**
     * @ORM\ManyToMany(targetEntity=HistoriqueCommande::class, mappedBy="commande")
     */
    private $historiqueCommandes;

    /**
     * @ORM\ManyToMany(targetEntity=Etat::class)
     */
    private $etat;

    public function __construct()
    {
        $this->historiqueCommandes = new ArrayCollection();
        $this->etat = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantiteCommande(): ?int
    {
        return $this->quantiteCommande;
    }

    public function setQuantiteCommande(int $quantiteCommande): self
    {
        $this->quantiteCommande = $quantiteCommande;

        return $this;
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

    public function getMontantHt(): ?float
    {
        return $this->montantHt;
    }

    public function setMontantHt(float $montantHt): self
    {
        $this->montantHt = $montantHt;

        return $this;
    }

    public function getMontantTtc(): ?float
    {
        return $this->montantTtc;
    }

    public function setMontantTtc(float $montantTtc): self
    {
        $this->montantTtc = $montantTtc;

        return $this;
    }

    public function getTransporteur(): ?string
    {
        return $this->transporteur;
    }

    public function setTransporteur(string $transporteur): self
    {
        $this->transporteur = $transporteur;

        return $this;
    }

    /**
     * @return Collection<int, HistoriqueCommande>
     */
    public function getHistoriqueCommandes(): Collection
    {
        return $this->historiqueCommandes;
    }

    public function addHistoriqueCommande(HistoriqueCommande $historiqueCommande): self
    {
        if (!$this->historiqueCommandes->contains($historiqueCommande)) {
            $this->historiqueCommandes[] = $historiqueCommande;
            $historiqueCommande->addCommande($this);
        }

        return $this;
    }

    public function removeHistoriqueCommande(HistoriqueCommande $historiqueCommande): self
    {
        if ($this->historiqueCommandes->removeElement($historiqueCommande)) {
            $historiqueCommande->removeCommande($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Etat>
     */
    public function getEtat(): Collection
    {
        return $this->etat;
    }

    public function addEtat(Etat $etat): self
    {
        if (!$this->etat->contains($etat)) {
            $this->etat[] = $etat;
        }

        return $this;
    }

    public function removeEtat(Etat $etat): self
    {
        $this->etat->removeElement($etat);

        return $this;
    }
}
