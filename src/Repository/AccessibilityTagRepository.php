<?php

namespace App\Repository;

use App\Entity\AccessibilityTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AccessibilityTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessibilityTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessibilityTag[]    findAll()
 * @method AccessibilityTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessibilityTagRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AccessibilityTag::class);
    }

    // /**
    //  * @return AccessibilityTag[] Returns an array of AccessibilityTag objects
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
    public function findOneBySomeField($value): ?AccessibilityTag
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
