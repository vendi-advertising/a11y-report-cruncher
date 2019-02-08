<?php

namespace App\Repository;

use App\Entity\UrlLogEntryDirection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UrlLogEntryDirection|null find($id, $lockMode = null, $lockVersion = null)
 * @method UrlLogEntryDirection|null findOneBy(array $criteria, array $orderBy = null)
 * @method UrlLogEntryDirection[]    findAll()
 * @method UrlLogEntryDirection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrlLogEntryDirectionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UrlLogEntryDirection::class);
    }

    // /**
    //  * @return UrlLogEntryDirection[] Returns an array of UrlLogEntryDirection objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UrlLogEntryDirection
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
