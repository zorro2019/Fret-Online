<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * voyage
 *
 * @ORM\Entity(repositoryClass="App\Repository\VoyageRepository")
 */
class Voyage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @Assert\Regex("/^[a-zA-Z]+$/")
     * @var string
     * @ORM\Column(name="villeDepart", type="string", length=50, nullable=false)
     */
    private $villedepart;

    /**
     * @Assert\Regex("/^[a-zA-Z]+$/")
     * @var string
     * @ORM\Column(name="villeArrive", type="string", length=50, nullable=false)
     */
    private $villearrive;

    /**
     * @Assert\Range(min="1",max="60")
     * @var int
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=false, options={"default"="1"})
     */
    private $status = '1';


    /**
     * @ORM\Column(type="datetime")
     */
    private $debut_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vehicule", inversedBy="voyage")
     */
    private $idVehicule;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DetailsVoyage", mappedBy="idVoyage")
     */
    private $ListeDetailVoyage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $produits;
    /**
     * @var bool
     */
    private $nouveauVoyage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantiteTotal;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chauffeur",inversedBy="voyage",fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idChauffeur;

    public function __construct()
    {
        $this->ListeDetailVoyage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVilledepart(): ?string
    {
        return $this->villedepart;
    }

    public function setVilledepart(string $villedepart): self
    {
        $this->villedepart = $villedepart;

        return $this;
    }

    public function getVillearrive(): ?string
    {
        return $this->villearrive;
    }

    public function setVillearrive(string $villearrive): self
    {
        $this->villearrive = $villearrive;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }



    public function getDebutAt(): ?\DateTimeInterface
    {
        return $this->debut_at;
    }

    public function setDebutAt(\DateTimeInterface $debut_at): self
    {
        $this->debut_at = $debut_at;

        return $this;
    }

    public function getIdVehicule(): ?Vehicule
    {
        return $this->idVehicule;
    }

    public function setIdVehicule(?Vehicule $idVehicule): self
    {
        $this->idVehicule = $idVehicule;

        return $this;
    }

    /**
     * @return bool
     */
    public function getNouveauVoyage()
    {
        return $this->nouveauVoyage;
    }

    /**
     * @param mixed $nouveauVoyage
     * @return void
     */
    public function setNouveauVoyage($nouveauVoyage)
    {
        $this->nouveauVoyage = $nouveauVoyage;
    }



    /**
     * @return Collection|DetailsVoyage[]
     */
    public function getListeDetailVoyage(): Collection
    {
        return $this->ListeDetailVoyage;
    }

    public function addListeDetailVoyage(DetailsVoyage $listeDetailVoyage): self
    {
        if (!$this->ListeDetailVoyage->contains($listeDetailVoyage)) {
            $this->ListeDetailVoyage[] = $listeDetailVoyage;
            $listeDetailVoyage->setIdVoyage($this);
        }

        return $this;
    }

    public function removeListeDetailVoyage(DetailsVoyage $listeDetailVoyage): self
    {
        if ($this->ListeDetailVoyage->contains($listeDetailVoyage)) {
            $this->ListeDetailVoyage->removeElement($listeDetailVoyage);
            // set the owning side to null (unless already changed)
            if ($listeDetailVoyage->getIdVoyage() === $this) {
                $listeDetailVoyage->setIdVoyage(null);
            }
        }

        return $this;
    }

    public function getProduits(): ?string
    {
        return $this->produits;
    }

    public function setProduits(?string $produits): self
    {
        $this->produits = $produits;

        return $this;
    }

    public function getQuantiteTotal(): ?int
    {
        return $this->quantiteTotal;
    }

    public function setQuantiteTotal(?int $quantiteTotal): self
    {
        $this->quantiteTotal = $quantiteTotal;

        return $this;
    }

    public function getQuantiteDetail()
    {
        $total = $this->getQuantite();
        foreach ($this->getListeDetailVoyage() as $dt){
            if ($dt->getCharge() != null){
                $total = $total+$dt->getCharge();
            }
            if ($dt->getDecharge() != null){
                $total = $total-$dt->getDecharge();
            }
        }
        return $total;
    }

    /**
     * @return mixed
     */
    public function getIdChauffeur()
    {
        return $this->idChauffeur;
    }

    /**
     * @param mixed $idChauffeur
     * @return Voyage
     */
    public function setIdChauffeur($idChauffeur)
    {
        $this->idChauffeur = $idChauffeur;
        return $this;
    }


}
