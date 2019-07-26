<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlerteRepository")
 */
class Alerte
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creat_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $to_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $from_id;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $nbre_vehicule;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $read_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $villeDepart;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $villeArrive;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $debut_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $finish_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contenu_alerte;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $typeVehicule;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatAt(): ?\DateTimeInterface
    {
        return $this->creat_at;
    }

    public function setCreatAt(\DateTimeInterface $creat_at): self
    {
        $this->creat_at = $creat_at;

        return $this;
    }

    public function getToId(): ?int
    {
        return $this->to_id;
    }

    public function setToId(int $to_id): self
    {
        $this->to_id = $to_id;

        return $this;
    }

    public function getFromId(): ?int
    {
        return $this->from_id;
    }

    public function setFromId(int $from_id): self
    {
        $this->from_id = $from_id;

        return $this;
    }

    public function getNbreVehicule(): ?int
    {
        return $this->nbre_vehicule;
    }

    public function setNbreVehicule(int $nbre_vehicule): self
    {
        $this->nbre_vehicule = $nbre_vehicule;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReadAt()
    {
        return $this->read_at;
    }

    /**
     * @param mixed $read_at
     * @return Alerte
     */
    public function setReadAt($read_at)
    {
        $this->read_at = $read_at;
        return $this;
    }



    public function getVilleDepart(): ?string
    {
        return $this->villeDepart;
    }

    public function setVilleDepart(?string $villeDepart): self
    {
        $this->villeDepart = $villeDepart;

        return $this;
    }

    public function getVilleArrive(): ?string
    {
        return $this->villeArrive;
    }

    public function setVilleArrive(?string $villeArrive): self
    {
        $this->villeArrive = $villeArrive;

        return $this;
    }

    public function getDebutAt(): ?\DateTimeInterface
    {
        return $this->debut_at;
    }

    public function setDebutAt(?\DateTimeInterface $debut_at): self
    {
        $this->debut_at = $debut_at;

        return $this;
    }

    public function getFinishAt(): ?\DateTimeInterface
    {
        return $this->finish_at;
    }

    public function setFinishAt(?\DateTimeInterface $finish_at): self
    {
        $this->finish_at = $finish_at;

        return $this;
    }

    public function getContenuAlerte(): ?string
    {
        return $this->contenu_alerte;
    }

    public function setContenuAlerte(?string $contenu_alerte): self
    {
        $this->contenu_alerte = $contenu_alerte;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTypeVehicule()
    {
        return $this->typeVehicule;
    }

    /**
     * @param mixed $typeVehicule
     * @return Alerte
     */
    public function setTypeVehicule($typeVehicule)
    {
        $this->typeVehicule = $typeVehicule;
        return $this;
    }


}
