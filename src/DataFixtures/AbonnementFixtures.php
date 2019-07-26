<?php

namespace App\DataFixtures;

use App\Entity\Abonnement;
use App\Repository\AbonnesRepository;
use App\Repository\VehiculeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AbonnementFixtures extends Fixture
{
    private $repo;
    public function __construct(VehiculeRepository $repo)
    {
        $this->repo = $repo;
    }
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $ab = new Abonnement();
        $ab->setCreateAt(new \DateTime());
        $ab->setFinishAt(new \DateTime());
        $ab->setIdVehicule($this->repo->find(1));
        $type = Abonnement::TYPEABONNEMENT[4];
        $ab->setTypeAbonnement($type[1]);
        $ab->setMontant($type[2]);
        $manager->persist($ab);
        $manager->flush();
    }
}
