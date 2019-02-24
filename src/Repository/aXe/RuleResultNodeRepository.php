<?php

namespace App\Repository\aXe;

use App\Entity\aXe\RuleResultNode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RuleResultNode|null find($id, $lockMode = null, $lockVersion = null)
 * @method RuleResultNode|null findOneBy(array $criteria, array $orderBy = null)
 * @method RuleResultNode[]    findAll()
 * @method RuleResultNode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RuleResultNodeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RuleResultNode::class);
    }

    // /**
    //  * @return RuleResultNode[] Returns an array of RuleResultNode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RuleResultNode
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
