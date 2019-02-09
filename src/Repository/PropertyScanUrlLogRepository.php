<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\PropertyScanUrlLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PropertyScanUrlLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropertyScanUrlLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropertyScanUrlLog[]    findAll()
 * @method PropertyScanUrlLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyScanUrlLogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PropertyScanUrlLog::class);
    }

    // /**
    //  * @return PropertyScanUrlLog[] Returns an array of PropertyScanUrlLog objects
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
    public function findOneBySomeField($value): ?PropertyScanUrlLog
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
