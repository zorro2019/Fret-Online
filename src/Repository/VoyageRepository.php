<?php

namespace App\Repository;

use App\Entity\Rechercher;
use App\Entity\Voyage;
use App\Form\RechercherType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Voyage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voyage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voyage[]    findAll()
 * @method Voyage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoyageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Voyage::class);
    }

    /**
     * @param $value
     * @return voyage[] Returns an array of voyage objects
     */

    public function findVoyageActif($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.idVehicule = :val')
            ->andWhere('v.status = 1')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findListVoyageActif()
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.status = 1')
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findListVoyageActifQuery(Rechercher $search, $type)
    {
        $query =  $this->createQueryBuilder('v')
            ->andWhere('v.status = 1')
            ->orderBy('v.debut_at', 'ASC')
            ;
        if ($search->getTonnage()){
            $query->where('v.quantite > :tonnage');
        }
        return $query->getQuery();
    }


    /*
    public function findOneBySomeField($value): ?voyage
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
