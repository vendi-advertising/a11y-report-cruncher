<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\PropertyUrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PropertyUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropertyUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropertyUrl[]    findAll()
 * @method PropertyUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyUrlRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PropertyUrl::class);
    }

    // /**
    //  * @return PropertyUrl[] Returns an array of PropertyUrl objects
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
    public function findOneBySomeField($value): ?PropertyUrl
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
