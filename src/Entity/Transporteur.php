<?php

namespace App\Entity;

use App\Repository\TransporteurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransporteurRepository::class)
 */
class Transporteur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $tarif;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $suivi;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numeroSuivi;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }
    public function __toString()
    {
        return $this->getNom() . '[br]' . $this->getDescription() . '[br]' . $this->getTarif() . ' ' . '[€]';
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(float $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getSuivi(): ?string
    {
        return $this->suivi;
    }

    public function setSuivi(?string $suivi): self
    {
        $this->suivi = $suivi;

        return $this;
    }

    public function getNumeroSuivi(): ?string
    {
        return $this->numeroSuivi;
    }

    public function setNumeroSuivi(?string $numeroSuivi): self
    {
        $this->numeroSuivi = $numeroSuivi;

        return $this;
    }
}
