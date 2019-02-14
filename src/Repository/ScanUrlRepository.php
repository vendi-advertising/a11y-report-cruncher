<?php

namespace App\Repository;

use App\Entity\ScanUrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ScanUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScanUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScanUrl[]    findAll()
 * @method ScanUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScanUrlRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ScanUrl::class);
    }

    public function findAllUrlsReadyToScan(int $limit = 20) : array
    {
        return $this
                    ->findBy(
                        [
                            'scanStatus' => ScanUrl::SCAN_STATUS_READY,
                        ],
                        null,
                        $limit
                    )
                ;
    }

    // /**
    //  * @return ScanUrl[] Returns an array of ScanUrl objects
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
    public function findOneBySomeField($value): ?ScanUrl
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
