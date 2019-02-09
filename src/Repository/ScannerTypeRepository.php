<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\ScannerType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ScannerType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScannerType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScannerType[]    findAll()
 * @method ScannerType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScannerTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ScannerType::class);
    }

    // /**
    //  * @return ScannerType[] Returns an array of ScannerType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ScannerType
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
