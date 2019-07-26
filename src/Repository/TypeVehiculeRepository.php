<?php

namespace App\Repository;

use App\Entity\TypeVehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeVehicule|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeVehicule|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeVehicule[]    findAll()
 * @method TypeVehicule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeVehiculeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeVehicule::class);
    }

    // /**
    //  * @return TypeVehicule[] Returns an array of TypeVehicule objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeVehicule
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
