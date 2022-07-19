<?php

namespace App\Entity;

use App\Repository\PrescriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrescriptionRepository::class)
 */
class Prescription
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
    private $libellePrescrip;

    /**
     * @ORM\ManyToOne(targetEntity=Dossier::class, inversedBy="dossiers", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE",nullable=true)
     */
    private $dossiers;

    /**
     * @ORM\ManyToOne(targetEntity=TypePrescription::class, inversedBy="prescriptiontype", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE",nullable=true)
     *
     */
    private $prescriptiontype;


    /**
     * @ORM\ManyToMany(targetEntity=Prestation::class, mappedBy="prestations", cascade={"persist"})
     */
    private $prestations;

    /**
     * @ORM\ManyToOne(targetEntity=Structure::class, inversedBy="structures", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE",nullable=true)
     */
    private $structures;

    public function __construct()
    {
        $this->prestations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibellePrescrip(): ?string
    {
        return $this->libellePrescrip;
    }

    public function setLibellePrescrip(string $libellePrescrip): self
    {
        $this->libellePrescrip = $libellePrescrip;

        return $this;
    }

    public function getDossiers(): ?Dossier
    {
        return $this->dossiers;
    }

    public function setDossiers(?Dossier $dossiers): self
    {
        $this->dossiers = $dossiers;

        return $this;
    }

    public function getPrescriptiontype(): ?TypePrescription
    {
        return $this->prescriptiontype;
    }

    public function setPrescriptiontype(?TypePrescription $prescriptiontype): self
    {
        $this->prescriptiontype = $prescriptiontype;

        return $this;
    }



    /**
     * @return Collection|Prestation[]
     */
    public function getPrestations(): Collection
    {
        return $this->prestations;
    }

    public function addPrestation(Prestation $prestation): self
    {
        if (!$this->prestations->contains($prestation)) {
            $this->prestations[] = $prestation;
            $prestation->addPrestation($this);
        }

        return $this;
    }

    public function removePrestation(Prestation $prestation): self
    {
        if ($this->prestations->removeElement($prestation)) {
            $prestation->removePrestation($this);
        }

        return $this;
    }

    public function getStructures(): ?Structure
    {
        return $this->structures;
    }

    public function setStructures(?Structure $structures): self
    {
        $this->structures = $structures;

        return $this;
    }
}
