<?php

namespace App\Repository;

use App\Entity\DetailsVoyage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DetailsVoyage|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailsVoyage|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailsVoyage[]    findAll()
 * @method DetailsVoyage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetailsVoyageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DetailsVoyage::class);
    }

    // /**
    //  * @return DetailsVoyage[] Returns an array of DetailsVoyage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DetailsVoyage
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
