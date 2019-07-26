<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChauffeurRepository")
 */
class Chauffeur
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Tel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Adresse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Abonnes", inversedBy="Chauffeurs")
     */
    private $idAbonnes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Voyage", mappedBy="IdChauffeur")
     */
    private $voyage;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->Tel;
    }

    public function setTel(string $Tel): self
    {
        $this->Tel = $Tel;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getIdAbonnes(): ?Abonnes
    {
        return $this->idAbonnes;
    }

    public function setIdAbonnes(?Abonnes $idAbonnes): self
    {
        $this->idAbonnes = $idAbonnes;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVoyage()
    {
        return $this->voyage;
    }

    /**
     * @param mixed $voyage
     * @return Chauffeur
     */
    public function setVoyage($voyage)
    {
        $this->voyage = $voyage;
        return $this;
    }



}
