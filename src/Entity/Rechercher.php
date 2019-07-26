<?php
namespace App\Entity;

class Rechercher{
    /**
     * @var string|null
     */
    private $localite;
    /**
     * @var int|null
     */
    private $tonnage;
    /**
     * @var TypeVehicule|null
     */
    private $typeVehicule;
    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getLocalite()
    {
        return $this->localite;
    }

    /**
     * @param mixed $localite
     */
    public function setLocalite($localite): void
    {
        $this->localite = $localite;
    }

    /**
     * @return mixed
     */
    public function getTonnage()
    {
        return $this->tonnage;
    }

    /**
     * @param mixed $tonnage
     */
    public function setTonnage($tonnage): void
    {
        $this->tonnage = $tonnage;
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

    public function getTypeData()
    {

    }


}