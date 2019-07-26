<?php

namespace App\Repository;

use App\Entity\Entreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Entreprise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entreprise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entreprise[]    findAll()
 * @method Entreprise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntrepriseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Entreprise::class);
    }

    /**
     * @param $value
     * @return Entreprise[] Returns an array of Entreprise objects
     */

    public function findByNineaField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.ninea = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }



    public function findAllEnreprise(): ?Entreprise
    {
        $var = $this->createQueryBuilder('e');
            $var1 = $var->select('e')
                ->join('e.abonnes','ab')
                ->join('ab.listeVehicule','vh')
                ->join('vh.voyage', 'vg')
                ->addSelect('vg')
                ->groupBy('e.nom')
            ->getQuery()
            ->getResult()
        ;
    }

}
