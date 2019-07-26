<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DetailsVoyageRepository")
 */
class DetailsVoyage
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
    private $ville;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateDepart;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $charge;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $decharge;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Voyage", inversedBy="ListeDetailVoyage")
     */
    private $idVoyage;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $position;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(?\DateTimeInterface $dateDepart): self
    {
        $this->dateDepart = $dateDepart;

        return $this;
    }

    public function getCharge(): ?int
    {
        return $this->charge;
    }

    public function setCharge(?int $charge): self
    {
        $this->charge = $charge;

        return $this;
    }

    public function getDecharge(): ?int
    {
        return $this->decharge;
    }

    public function setDecharge(?int $decharge): self
    {
        $this->decharge = $decharge;

        return $this;
    }

    public function getIdVoyage(): ?Voyage
    {
        return $this->idVoyage;
    }

    public function setIdVoyage(?Voyage $idVoyage): self
    {
        $this->idVoyage = $idVoyage;

        return $this;
    }

    public function getPosition(): ?bool
    {
        return $this->position;
    }

    public function setPosition(?bool $position): self
    {
        $this->position = $position;

        return $this;
    }
}
