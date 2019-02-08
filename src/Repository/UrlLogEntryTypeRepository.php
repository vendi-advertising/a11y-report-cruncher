<?php

namespace App\Repository;

use App\Entity\UrlLogEntryType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UrlLogEntryType|null find($id, $lockMode = null, $lockVersion = null)
 * @method UrlLogEntryType|null findOneBy(array $criteria, array $orderBy = null)
 * @method UrlLogEntryType[]    findAll()
 * @method UrlLogEntryType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrlLogEntryTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UrlLogEntryType::class);
    }

    // /**
    //  * @return UrlLogEntryType[] Returns an array of UrlLogEntryType objects
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
    public function findOneBySomeField($value): ?UrlLogEntryType
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
