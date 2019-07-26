<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AbonnementRepository")
 */
class Abonnement
{
    const TYPEABONNEMENT = array(
       1 => array(
                1 => 'Mensuel',
                2 => 5000
        ),
        2 => array(
            1 => 'Trimestruel',
            2 => 15000
        ),
        3 => array(
            1 => 'Semestruel',
            2 => 25000
        ),
        4 => array(
            1 => 'Annuel',
            2 => 40000
        )

    );
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeAbonnement;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $montant;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Vehicule", inversedBy="abonnement")
     */
    private $vehicule;

    public function __construct()
    {
        $this->vehicule = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeAbonnement(): ?string
    {
        return $this->typeAbonnement;
    }

    public function setTypeAbonnement(string $typeAbonnement): self
    {
        $this->typeAbonnement = $typeAbonnement;

        return $this;
    }

    public function getMontant()
    {
        return $this->montant;
    }

    public function setMontant($montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * @return Collection|Vehicule[]
     */
    public function getVehicule(): Collection
    {
        return $this->vehicule;
    }

    public function addVehicule(Vehicule $vehicule): self
    {
        if (!$this->vehicule->contains($vehicule)) {
            $this->vehicule[] = $vehicule;
        }

        return $this;
    }

    public function removeVehicule(Vehicule $vehicule): self
    {
        if ($this->vehicule->contains($vehicule)) {
            $this->vehicule->removeElement($vehicule);
        }

        return $this;
    }
}
