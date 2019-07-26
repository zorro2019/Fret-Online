<?php

namespace App\Repository;

use App\Entity\ZoneIntervention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ZoneIntervention|null find($id, $lockMode = null, $lockVersion = null)
 * @method ZoneIntervention|null findOneBy(array $criteria, array $orderBy = null)
 * @method ZoneIntervention[]    findAll()
 * @method ZoneIntervention[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZoneInterventionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ZoneIntervention::class);
    }

    // /**
    //  * @return ZoneIntervention[] Returns an array of ZoneIntervention objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('z.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ZoneIntervention
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
