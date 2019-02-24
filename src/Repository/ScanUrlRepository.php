<?php declare(strict_types=1);

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

    public function findUrlsReadyForA11y(int $limit = 20) : array
    {
        return $this
                ->createQueryBuilder('su')
                ->select('su')
                ->leftJoin('su.scan', 's')
                ->leftJoin('su.scanResult', 'sr')
                ->andWhere('s.scanType LIKE :scan_type')
                ->andWhere('su.contentType LIKE :content_type')
                ->andWhere('su.scanStatus = :scan_status')
                ->andWhere('sr.id IS NULL')
                ->setParameter('scan_type', '%accessibility%')
                ->setParameter('content_type', '%text/html%')
                ->setParameter('scan_status', 'SCAN_STATUS_SUCCESS')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult()
            ;
    }

    /*
SELECT
    *
FROM
    scan s
LEFT JOIN
    scan_url su
ON
    s.id = su.scan_id
WHERE
    s.scan_type LIKE '%accessibility%'
AND
    su.content_type LIKE '%text/html%'
AND
    su.scan_status = 'SCAN_STATUS_READY'
AND
    su.http_status = 200
LIMIT
    5
;
    */

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
