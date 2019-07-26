<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Vehicule
 *
 * @ORM\Entity(repositoryClass="App\Repository\VehiculeRepository")
 * @UniqueEntity("matricule")
 */
class Vehicule
{
    const TYPEVEHICULE = array(
        1 => 'Camion',
        2 => 'Camion Citerne',
        3 => 'Camion Frigorifique'
    );
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @Assert\Regex("/^([a-zA-Z]{2})([0-9]{4})([a-zA-Z]{2})$/")
     * @ORM\Column(name="matricule", type="string", length=70, nullable=false)
     */
    private $matricule;

    /**
     * @var string|null
     * @ORM\Column(name="model", type="string", length=100, nullable=true)
     */
    private $model;

    /**
     * @Assert\Range(min="5",max="100")
     * @var int|null
     * @ORM\Column(name="tonnage", type="integer", nullable=true)
     */
    private $tonnage;

    /**
     * @Assert\Range(min="0", max="10")
     * @var int|null
     *
     * @ORM\Column(name="age_vehicule", type="integer", nullable=true)
     */
    private $ageVehicule;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Abonnes",inversedBy="listeVehicule")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idAbonne;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Voyage", mappedBy="idVehicule")
     */
    private $voyage;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creat_at;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Abonnement", mappedBy="vehicule")
     */
    private $abonnement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeVehicule")
     */
    private $typeVehicule;

    public function __construct()
    {
        $this->voyage = new ArrayCollection();
        $this->abonnement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getTonnage(): ?int
    {
        return $this->tonnage;
    }

    public function setTonnage(?int $tonnage): self
    {
        $this->tonnage = $tonnage;

        return $this;
    }

    public function getAgeVehicule(): ?int
    {
        return $this->ageVehicule;
    }

    public function setAgeVehicule(?int $ageVehicule): self
    {
        $this->ageVehicule = $ageVehicule;

        return $this;
    }

    public function getIdAbonne(): ?Abonnes
    {
        return $this->idAbonne;
    }

    public function setIdAbonne(?Abonnes $idAbonne): self
    {
        $this->idAbonne = $idAbonne;

        return $this;
    }

    /**
     * @return Collection|Voyage[]
     */
    public function getVoyage(): Collection
    {
        return $this->voyage;
    }

    public function addVoyage(Voyage $voyage): self
    {
        if (!$this->voyage->contains($voyage)) {
            $this->voyage[] = $voyage;
            $voyage->setIdVehicule($this);
        }

        return $this;
    }

    public function removeVoyage(Voyage $voyage): self
    {
        if ($this->voyage->contains($voyage)) {
            $this->voyage->removeElement($voyage);
            // set the owning side to null (unless already changed)
            if ($voyage->getIdVehicule() === $this) {
                $voyage->setIdVehicule(null);
            }
        }

        return $this;
    }

    /**
     * @param mixed $type_vehicule
     */
    public function setTypeVehicule($type_vehicule): void
    {
        $this->typeVehicule = $type_vehicule;
    }

    public function getCreatAt(): ?\DateTimeInterface
    {
        return $this->creat_at;
    }

    public function setCreatAt(\DateTimeInterface $creat_at): self
    {
        $this->creat_at = $creat_at;
        $this->creat_at = new \DateTime('now');
        return $this;
    }

    /**
     * @return Collection|Abonnement[]
     */
    public function getAbonnement(): Collection
    {
        return $this->abonnement;
    }

    public function addAbonnement(Abonnement $abonnement): self
    {
        if (!$this->abonnement->contains($abonnement)) {
            $this->abonnement[] = $abonnement;
            $abonnement->addVehicule($this);
        }
        return $this;
    }

    public function removeAbonnement(Abonnement $abonnement): self
    {
        if ($this->abonnement->contains($abonnement)) {
            $this->abonnement->removeElement($abonnement);
            $abonnement->removeVehicule($this);
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTypeVehicule(): ?TypeVehicule
    {
        return $this->typeVehicule;
    }


    public function getMatricule()
    {
        return $this->matricule;
    }

    /**
     * @param string $matricule
     */
    public function setMatricule(string $matricule): void
    {
        $this->matricule = $matricule;
    }


}
