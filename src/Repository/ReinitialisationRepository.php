<?php

namespace App\Repository;

use App\Entity\Reinitialisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Reinitialisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reinitialisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reinitialisation[]    findAll()
 * @method Reinitialisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReinitialisationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reinitialisation::class);
    }

    // /**
    //  * @return Reinitialisation[] Returns an array of Reinitialisation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reinitialisation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
