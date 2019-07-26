<?php

namespace App\DataFixtures;

use App\Entity\Abonnes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AbonnesFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /*$ab = new Abonnes();
        $ab->setNom("baba");
        $ab->setEmail('baba@baba.com');
        $ab->setPassword($this->encoder->encodePassword($ab,"baba2018"));
        $ab->setNinea("123243ML");
        $ab->setTelephone('778213119');
        $ab->setFile("hfbdsfbshfsdfsd");
        $ab->setTypeAbonne(2);
        $manager->persist($ab);*/
        $manager->flush();
    }
}
