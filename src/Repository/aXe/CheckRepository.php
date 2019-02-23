<?php

namespace App\Repository\aXe;

use App\Entity\aXe\Check;
use Psr\SimpleCache\CacheInterface;
use App\Repository\SimpleCacheRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Check|null find($id, $lockMode = null, $lockVersion = null)
 * @method Check|null findOneBy(array $criteria, array $orderBy = null)
 * @method Check[]    findAll()
 * @method Check[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CheckRepository extends SimpleCacheRepository
{
    public function __construct(RegistryInterface $registry, CacheInterface $cache)
    {
        parent::__construct($registry, Check::class, $cache);
    }

    public function set_properties_on_new($obj, $props)
    {
        parent::set_properties_on_new($obj, $props);
        $obj->setImpact($props['impact']);
        $obj->setType($props['type']);
    }

    public function findAllLoaded()
    {
        return $this
                ->createQueryBuilder('a')
                ->select('a')
                ->getQuery()
                ->getResult()
        ;
    }

    // /**
    //  * @return Check[] Returns an array of Check objects
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
    public function findOneBySomeField($value): ?Check
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
