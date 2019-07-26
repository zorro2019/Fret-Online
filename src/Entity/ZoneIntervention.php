<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ZoneInterventionRepository")
 */
class ZoneIntervention
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomZone;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Entreprise", inversedBy="ListeZone")
     */
    private $idEntreprise;

    public function __construct()
    {
        $this->idEntreprise = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomZone(): ?string
    {
        return $this->nomZone;
    }

    public function setNomZone(string $nomZone): self
    {
        $this->nomZone = $nomZone;

        return $this;
    }

    /**
     * @return Collection|Entreprise[]
     */
    public function getIdEntreprise(): Collection
    {
        return $this->idEntreprise;
    }

    public function addIdEntreprise(Entreprise $idEntreprise): self
    {
        if (!$this->idEntreprise->contains($idEntreprise)) {
            $this->idEntreprise[] = $idEntreprise;
        }

        return $this;
    }

    public function removeIdEntreprise(Entreprise $idEntreprise): self
    {
        if ($this->idEntreprise->contains($idEntreprise)) {
            $this->idEntreprise->removeElement($idEntreprise);
        }

        return $this;
    }
}
