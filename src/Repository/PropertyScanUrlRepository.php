<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\PropertyScanUrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PropertyScanUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropertyScanUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropertyScanUrl[]    findAll()
 * @method PropertyScanUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyScanUrlRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PropertyScanUrl::class);
    }

    // /**
    //  * @return PropertyScanUrl[] Returns an array of PropertyScanUrl objects
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
    public function findOneBySomeField($value): ?PropertyScanUrl
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
