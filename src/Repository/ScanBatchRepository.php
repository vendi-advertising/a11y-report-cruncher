<?php

namespace App\Repository;

use App\Entity\ScanBatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ScanBatch|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScanBatch|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScanBatch[]    findAll()
 * @method ScanBatch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScanBatchRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ScanBatch::class);
    }

    // /**
    //  * @return ScanBatch[] Returns an array of ScanBatch objects
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
    public function findOneBySomeField($value): ?ScanBatch
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
