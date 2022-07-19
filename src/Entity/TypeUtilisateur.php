<?php

namespace App\Entity;

use App\Repository\TypeUtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeUtilisateurRepository::class)
 */
class TypeUtilisateur
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
    private $libellestypeUtilisat;


    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="utilisateur")
     */
    private $utilisateur;


    public function __construct()
    {
        $this->utilisateurs = new ArrayCollection();
        $this->typeuser = new ArrayCollection();
        $this->utilisateur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibellestypeUtilisat(): ?string
    {
        return $this->libellestypeUtilisat;
    }

    public function setLibellestypeUtilisat(string $libellestypeUtilisat): self
    {
        $this->libellestypeUtilisat = $libellestypeUtilisat;

        return $this;
    }


    /**
     * @return Collection|User[]
     */
    public function getUtilisateur(): Collection
    {
        return $this->utilisateur;
    }

    public function addUtilisateur(User $utilisateur): self
    {
        if (!$this->utilisateur->contains($utilisateur)) {
            $this->utilisateur[] = $utilisateur;
            $utilisateur->setUtilisateur($this);
        }

        return $this;
    }

    public function removeUtilisateur(User $utilisateur): self
    {
        if ($this->utilisateur->removeElement($utilisateur)) {
            // set the owning side to null (unless already changed)
            if ($utilisateur->getUtilisateur() === $this) {
                $utilisateur->setUtilisateur(null);
            }
        }

        return $this;
    }


}
