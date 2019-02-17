<?php

namespace App\Repository;

use App\Entity\AccessibilityCheckVersion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AccessibilityCheckVersion|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessibilityCheckVersion|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessibilityCheckVersion[]    findAll()
 * @method AccessibilityCheckVersion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessibilityCheckVersionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AccessibilityCheckVersion::class);
    }

    // /**
    //  * @return AccessibilityCheckVersion[] Returns an array of AccessibilityCheckVersion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AccessibilityCheckVersion
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
