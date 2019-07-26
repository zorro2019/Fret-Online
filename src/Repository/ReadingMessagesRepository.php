<?php

namespace App\Repository;

use App\Entity\ReadingMessages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReadingMessages|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReadingMessages|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReadingMessages[]    findAll()
 * @method ReadingMessages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReadingMessagesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReadingMessages::class);
    }

    /**
     * @param $value
     * @return ReadingMessages[] Returns an array of ReadingMessages objects
     */

    public function findMessagesInread($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.idAbonne = :val')
            ->andWhere('r.readed = 0')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?ReadingMessages
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
