<?php
/**
 * Created by PhpStorm.
 * User: Administrateur
 * Date: 22/03/2019
 * Time: 00:44
 */

namespace App\Entity;


class ALertSearch
{
    /**
     * @var
     */
    private $typeVehicule;
    private $typeProduit;
    private $nbreVehicule;
    private $debutAt;
    private $finishAt;
    private $villeSepart;
    private $villeArrive;
    private $descriptionProduits;

    public function __construct()
    {

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
     */
    public function setTypeVehicule($typeVehicule): void
    {
        $this->typeVehicule = $typeVehicule;
    }

    /**
     * @return mixed
     */
    public function getTypeProduit()
    {
        return $this->typeProduit;
    }

    /**
     * @param mixed $typeProduit
     */
    public function setTypeProduit($typeProduit): void
    {
        $this->typeProduit = $typeProduit;
    }

    /**
     * @return mixed
     */
    public function getNbreVehicule()
    {
        return $this->nbreVehicule;
    }

    /**
     * @param mixed $nbreVehicule
     */
    public function setNbreVehicule($nbreVehicule): void
    {
        $this->nbreVehicule = $nbreVehicule;
    }

    /**
     * @return mixed
     */
    public function getDebutAt()
    {
        return $this->debutAt;
    }

    /**
     * @param mixed $debutAt
     */
    public function setDebutAt($debutAt): void
    {
        $this->debutAt = $debutAt;
    }

    /**
     * @return mixed
     */
    public function getFinishAt()
    {
        return $this->finishAt;
    }

    /**
     * @param mixed $finishAt
     */
    public function setFinishAt($finishAt): void
    {
        $this->finishAt = $finishAt;
    }

    /**
     * @return mixed
     */
    public function getVilleSepart()
    {
        return $this->villeSepart;
    }

    /**
     * @param mixed $villeSepart
     */
    public function setVilleSepart($villeSepart): void
    {
        $this->villeSepart = $villeSepart;
    }

    /**
     * @return mixed
     */
    public function getVilleArrive()
    {
        return $this->villeArrive;
    }

    /**
     * @param mixed $villeArrive
     */
    public function setVilleArrive($villeArrive): void
    {
        $this->villeArrive = $villeArrive;
    }

    /**
     * @return mixed
     */
    public function getDescriptionProduits()
    {
        return $this->descriptionProduits;
    }

    /**
     * @param mixed $descriptionProduits
     */
    public function setDescriptionProduits($descriptionProduits): void
    {
        $this->descriptionProduits = $descriptionProduits;
    }


}

