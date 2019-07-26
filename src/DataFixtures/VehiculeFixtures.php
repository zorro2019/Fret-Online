<?php

namespace App\DataFixtures;

use App\Entity\Vehicule;
use App\Repository\AbonnesRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class VehiculeFixtures extends Fixture
{
    private $repo;
    public function __construct(AbonnesRepository $repo)
    {
        $this->repo = $repo;
    }
    public function load(ObjectManager $manager)
    {
      /*  $veh = new Vehicule();
        $veh->setIdabonnees($this->repo->find(6));
        $veh->setAgeVehicule(2);
        $veh->setMatricule("AL 55 78 MD");
        $veh->setType("Renault 2 ponts 3 che");
        $veh->setTonnage(50);
        $manager->persist($veh);*/
        $manager->flush();
    }
}
