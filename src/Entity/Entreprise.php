<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass="App\Repository\EntrepriseRepository")
 * @Vich\Uploadable()
 * @UniqueEntity("Tel")
 */
class Entreprise
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Regex("/^[A-Za-z0-9 '-]*$/")
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @Assert\Regex("/^[0-9]{9}$/")
     * @ORM\Column(type="string", length=255)
     */
    private $ninea;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $img;
    /**
     * @Assert\Image(mimeTypes="image/jpeg")
     * @var File|null
     * @Vich\UploadableField( mapping="entreprise_logo",fileNameProperty="img")
     */
    private $imageLogo;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbreAbonnes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Abonnes", mappedBy="id_entreprise")
     */
    private $abonnes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Tel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Ville;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ZoneIntervention", mappedBy="idEntreprise")
     */
    private $ListeZone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $compagnieAssurance;

    private $nbreVehiculeVide;

    /**
     * @return mixed
     */
    public function getNbreVehiculeVide()
    {
        return $this->nbreVehiculeVide;
    }

    /**
     * @param mixed $nbreVehiculeVide
     */
    public function setNbreVehiculeVide($nbreVehiculeVide): void
    {
        $this->nbreVehiculeVide = $nbreVehiculeVide;
    }

    public function __construct()
    {
        $this->abonnes = new ArrayCollection();
        $this->ListeZone = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNinea(): ?string
    {
        return $this->ninea;
    }

    public function setNinea(string $ninea): self
    {
        $this->ninea = $ninea;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getNbreAbonne(): ?int
    {
        return $this->nbreAbonnes;
    }

    public function setNbreAbonne(?int $nbreAbonne): self
    {
        $this->nbreAbonnes = $nbreAbonne;

        return $this;
    }

    /**
     * @return Collection|Abonnes[]
     */
    public function getAbonnes(): Collection
    {
        return $this->abonnes;
    }

    public function addAbonne(Abonnes $abonne): self
    {
        if (!$this->abonnes->contains($abonne)) {
            $this->abonnes[] = $abonne;
            $abonne->setIdEntreprise($this);
        }

        return $this;
    }

    public function removeAbonne(Abonnes $abonne): self
    {
        if ($this->abonnes->contains($abonne)) {
            $this->abonnes->removeElement($abonne);
            // set the owning side to null (unless already changed)
            if ($abonne->getIdEntreprise() === $this) {
                $abonne->setIdEntreprise(null);
            }
        }

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->Tel;
    }

    public function setTel(?string $Tel): self
    {
        $this->Tel = $Tel;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(?string $Ville): self
    {
        $this->Ville = $Ville;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageLogo(): ?File
    {
        return $this->imageLogo;
    }

    /**
     * @param File|null $imageLogo
     */
    public function setImageLogo(?File $imageLogo): void
    {
        $this->imageLogo = $imageLogo;
        if ($this->imageLogo instanceof UploadedFile)
        {
            $this->create_at = new \DateTime('now');
        }
        $this->create_at = new \DateTime('now');
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }

    /**
     * @return Collection|ZoneIntervention[]
     */
    public function getListeZone(): Collection
    {
        return $this->ListeZone;
    }

    public function addListeZone(ZoneIntervention $listeZone): self
    {
        if (!$this->ListeZone->contains($listeZone)) {
            $this->ListeZone[] = $listeZone;
            $listeZone->addIdEntreprise($this);
        }

        return $this;
    }

    public function removeListeZone(ZoneIntervention $listeZone): self
    {
        if ($this->ListeZone->contains($listeZone)) {
            $this->ListeZone->removeElement($listeZone);
            $listeZone->removeIdEntreprise($this);
        }

        return $this;
    }

    public function getCompagnieAssurance(): ?string
    {
        return $this->compagnieAssurance;
    }

    public function setCompagnieAssurance(?string $compagnieAssurance): self
    {
        $this->compagnieAssurance = $compagnieAssurance;

        return $this;
    }

}
