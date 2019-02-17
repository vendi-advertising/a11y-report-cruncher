<?php

namespace App\Repository;

use App\Entity\AccessibilityCheckResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AccessibilityCheckResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessibilityCheckResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessibilityCheckResult[]    findAll()
 * @method AccessibilityCheckResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessibilityCheckResultRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AccessibilityCheckResult::class);
    }

    // /**
    //  * @return AccessibilityCheckResult[] Returns an array of AccessibilityCheckResult objects
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
    public function findOneBySomeField($value): ?AccessibilityCheckResult
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
