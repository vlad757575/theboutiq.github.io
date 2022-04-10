<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="date")
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Panier::class, mappedBy="utilisateur")
     */
    private $paniers;

    /**
     * @ORM\ManyToOne(targetEntity=HistoriqueCommande::class, inversedBy="utilisateur")
     */
    private $historiqueCommande;

    /**
     * @ORM\OneToMany(targetEntity=Adresse::class, mappedBy="utilisateur")
     */
    private $adresseLivraison;

    /**
     * @ORM\OneToMany(targetEntity=Adresse::class, mappedBy="utilisateur")
     */
    private $adresseFacturation;

    public function __construct()
    {
        $this->paniers = new ArrayCollection();
        $this->adresseLivraison = new ArrayCollection();
        $this->adresseFacturation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers[] = $panier;
            $panier->setUtilisateur($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getUtilisateur() === $this) {
                $panier->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function getHistoriqueCommande(): ?HistoriqueCommande
    {
        return $this->historiqueCommande;
    }

    public function setHistoriqueCommande(?HistoriqueCommande $historiqueCommande): self
    {
        $this->historiqueCommande = $historiqueCommande;

        return $this;
    }

    /**
     * @return Collection<int, Adresse>
     */
    public function getAdresseLivraison(): Collection
    {
        return $this->adresseLivraison;
    }

    public function addAdresseLivraison(Adresse $adresseLivraison): self
    {
        if (!$this->adresseLivraison->contains($adresseLivraison)) {
            $this->adresseLivraison[] = $adresseLivraison;
            $adresseLivraison->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAdresseLivraison(Adresse $adresseLivraison): self
    {
        if ($this->adresseLivraison->removeElement($adresseLivraison)) {
            // set the owning side to null (unless already changed)
            if ($adresseLivraison->getUtilisateur() === $this) {
                $adresseLivraison->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Adresse>
     */
    public function getAdresseFacturation(): Collection
    {
        return $this->adresseFacturation;
    }

    public function addAdresseFacturation(Adresse $adresseFacturation): self
    {
        if (!$this->adresseFacturation->contains($adresseFacturation)) {
            $this->adresseFacturation[] = $adresseFacturation;
            $adresseFacturation->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAdresseFacturation(Adresse $adresseFacturation): self
    {
        if ($this->adresseFacturation->removeElement($adresseFacturation)) {
            // set the owning side to null (unless already changed)
            if ($adresseFacturation->getUtilisateur() === $this) {
                $adresseFacturation->setUtilisateur(null);
            }
        }

        return $this;
    }
}
