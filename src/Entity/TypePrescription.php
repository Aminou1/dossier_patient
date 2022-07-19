<?php

namespace App\Entity;

use App\Repository\TypePrescriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypePrescriptionRepository::class)
 */
class TypePrescription
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
    private $libelletypeprescription;

    /**
     * @ORM\OneToMany(targetEntity=Prescription::class, mappedBy="prescriptiontype", orphanRemoval=true)
     */
    private $prescriptiontype;

    public function __construct()
    {
        $this->prescriptiontype = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelletypeprescription(): ?string
    {
        return $this->libelletypeprescription;
    }

    public function setLibelletypeprescription(string $libelletypeprescription): self
    {
        $this->libelletypeprescription = $libelletypeprescription;

        return $this;
    }

    /**
     * @return Collection|Prescription[]
     */
    public function getPrescriptiontype(): Collection
    {
        return $this->prescriptiontype;
    }

    public function addPrescriptiontype(Prescription $prescriptiontype): self
    {
        if (!$this->prescriptiontype->contains($prescriptiontype)) {
            $this->prescriptiontype[] = $prescriptiontype;
            $prescriptiontype->setPrescriptiontype($this);
        }

        return $this;
    }

    public function removePrescriptiontype(Prescription $prescriptiontype): self
    {
        if ($this->prescriptiontype->removeElement($prescriptiontype)) {
            // set the owning side to null (unless already changed)
            if ($prescriptiontype->getPrescriptiontype() === $this) {
                $prescriptiontype->setPrescriptiontype(null);
            }
        }

        return $this;
    }
}
