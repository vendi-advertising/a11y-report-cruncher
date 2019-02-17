<?php

namespace App\Repository;

use App\Entity\AccessibilityCheckResultRelatedNode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AccessibilityCheckResultRelatedNode|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessibilityCheckResultRelatedNode|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessibilityCheckResultRelatedNode[]    findAll()
 * @method AccessibilityCheckResultRelatedNode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessibilityCheckResultRelatedNodeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AccessibilityCheckResultRelatedNode::class);
    }

    // /**
    //  * @return AccessibilityCheckResultRelatedNode[] Returns an array of AccessibilityCheckResultRelatedNode objects
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
    public function findOneBySomeField($value): ?AccessibilityCheckResultRelatedNode
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
