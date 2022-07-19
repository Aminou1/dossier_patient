<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="fos_user")
 */
class User  extends BaseUser
{
    /**
     * @ORM\Id
     *@ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    public function getId(): ?int
    {
        return $this->id;
    }
       /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $addresse;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $age;

    /**
     * @ORM\ManyToOne(targetEntity=TypeUtilisateur::class, inversedBy="utilisateur")
     */
    private $utilisateur;

    /**
     * @ORM\OneToMany(targetEntity=Dossier::class, mappedBy="utilisateur")
     */
    private $utilisateurs;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $qualification;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $specialiteMedicale;

    public function __construct()
    {
        parent::__construct();
        $this->utilisateurs = new ArrayCollection();
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAddresse(): ?string
    {
        return $this->addresse;
    }

    public function setAddresse(string $addresse): self
    {
        $this->addresse = $addresse;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(string $age): self
    {
        $this->age = $age;

        return $this;
    }


    public function getUtilisateur(): ?TypeUtilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?TypeUtilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * @return Collection|Dossier[]
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Dossier $utilisateur): self
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs[] = $utilisateur;
            $utilisateur->setUtilisateur($this);
        }

        return $this;
    }

    public function removeUtilisateur(Dossier $utilisateur): self
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            // set the owning side to null (unless already changed)
            if ($utilisateur->getUtilisateur() === $this) {
                $utilisateur->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getQualification(): ?string
    {
        return $this->qualification;
    }

    public function setQualification(?string $qualification): self
    {
        $this->qualification = $qualification;

        return $this;
    }

    public function getSpecialiteMedicale(): ?string
    {
        return $this->specialiteMedicale;
    }

    public function setSpecialiteMedicale(?string $specialiteMedicale): self
    {
        $this->specialiteMedicale = $specialiteMedicale;

        return $this;
    }

   
}
