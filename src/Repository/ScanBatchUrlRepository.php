<?php

namespace App\Repository;

use App\Entity\ScanBatchUrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ScanBatchUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScanBatchUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScanBatchUrl[]    findAll()
 * @method ScanBatchUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScanBatchUrlRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ScanBatchUrl::class);
    }

    // /**
    //  * @return ScanBatchUrl[] Returns an array of ScanBatchUrl objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ScanBatchUrl
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
