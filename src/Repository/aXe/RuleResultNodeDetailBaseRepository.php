<?php

namespace App\Repository\aXe;

use App\Entity\aXe\RuleResultNodeDetails\RuleResultNodeDetailBase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RuleResultNodeDetailBase|null find($id, $lockMode = null, $lockVersion = null)
 * @method RuleResultNodeDetailBase|null findOneBy(array $criteria, array $orderBy = null)
 * @method RuleResultNodeDetailBase[]    findAll()
 * @method RuleResultNodeDetailBase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RuleResultNodeDetailBaseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RuleResultNodeDetailBase::class);
    }

    // /**
    //  * @return RuleResultNodeDetailBase[] Returns an array of RuleResultNodeDetailBase objects
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
    public function findOneBySomeField($value): ?RuleResultNodeDetailBase
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
