<?php

namespace App\Entity;

use App\Repository\PrestationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrestationRepository::class)
 */
class Prestation
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
    private $libellePresta;

    /**
     * @ORM\ManyToMany(targetEntity=Prescription::class, inversedBy="prestations")
     */
    private $prestations;

    /**
     * @ORM\ManyToOne(targetEntity=TypePrestation::class, inversedBy="prestations")
     */
    private $prestationtype;

    public function __construct()
    {
        $this->prestations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibellePresta(): ?string
    {
        return $this->libellePresta;
    }

    public function setLibellePresta(string $libellePresta): self
    {
        $this->libellePresta = $libellePresta;

        return $this;
    }

    /**
     * @return Collection|Prescription[]
     */
    public function getPrestations(): Collection
    {
        return $this->prestations;
    }

    public function addPrestation(Prescription $prestation): self
    {
        if (!$this->prestations->contains($prestation)) {
            $this->prestations[] = $prestation;
        }

        return $this;
    }

    public function removePrestation(Prescription $prestation): self
    {
        $this->prestations->removeElement($prestation);

        return $this;
    }

    public function getPrestationtype(): ?TypePrestation
    {
        return $this->prestationtype;
    }

    public function setPrestationtype(?TypePrestation $prestationtype): self
    {
        $this->prestationtype = $prestationtype;

        return $this;
    }
}
