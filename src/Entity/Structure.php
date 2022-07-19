<?php

namespace App\Entity;

use App\Repository\StructureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StructureRepository::class)
 */
class Structure
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nomStructure;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addresseStructure;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $contact;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;


    /**
     * @ORM\OneToMany(targetEntity=Prescription::class, mappedBy="structures")
     */
    private $structures;

    public function __construct()
    {
        $this->structures = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomStructure(): ?string
    {
        return $this->nomStructure;
    }

    public function setNomStructure(string $nomStructure): self
    {
        $this->nomStructure = $nomStructure;

        return $this;
    }

    public function getAddresseStructure(): ?string
    {
        return $this->addresseStructure;
    }

    public function setAddresseStructure(string $addresseStructure): self
    {
        $this->addresseStructure = $addresseStructure;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

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

    /**
     * @return Collection|Prescription[]
     */
    public function getStructures(): Collection
    {
        return $this->structures;
    }

    public function addStructure(Prescription $structure): self
    {
        if (!$this->structures->contains($structure)) {
            $this->structures[] = $structure;
            $structure->setStructures($this);
        }

        return $this;
    }

    public function removeStructure(Prescription $structure): self
    {
        if ($this->structures->removeElement($structure)) {
            // set the owning side to null (unless already changed)
            if ($structure->getStructures() === $this) {
                $structure->setStructures(null);
            }
        }

        return $this;
    }

}
