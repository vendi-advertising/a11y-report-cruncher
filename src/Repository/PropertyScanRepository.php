<?php

namespace App\Repository;

use App\Entity\PropertyScan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PropertyScan|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropertyScan|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropertyScan[]    findAll()
 * @method PropertyScan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyScanRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PropertyScan::class);
    }

    // /**
    //  * @return PropertyScan[] Returns an array of PropertyScan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PropertyScan
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
