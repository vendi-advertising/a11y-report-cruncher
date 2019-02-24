<?php declare(strict_types=1);

namespace App\Repository\aXe;

use App\Entity\aXe\ResultType;
use App\Repository\SimpleCacheRepository;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ResultType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResultType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResultType[]    findAll()
 * @method ResultType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultTypeRepository extends SimpleCacheRepository
{
    public function __construct(RegistryInterface $registry, CacheInterface $cache)
    {
        parent::__construct($registry, ResultType::class, $cache);
    }

    // /**
    //  * @return ResultType[] Returns an array of ResultType objects
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
    public function findOneBySomeField($value): ?ResultType
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
