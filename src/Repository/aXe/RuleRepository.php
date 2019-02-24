<?php declare(strict_types=1);

namespace App\Repository\aXe;

use App\Entity\aXe\Rule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Rule|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rule|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rule[]    findAll()
 * @method Rule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RuleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Rule::class);
    }

    public function get_rule(Rule $maybe_rule) : ?Rule
    {
        $qb = $this
                ->createQueryBuilder('r')
                ->select('r as real_rule, GROUP_CONCAT(DISTINCT c.name ORDER BY c.name ASC) as check_names')
                ->leftJoin('r.checks', 'c')
                ->andWhere('r.name = :rule')
                ->setParameter('rule', $maybe_rule->getName())
        ;

        $query = $qb->getQuery();

        $result = $query->getResult();

        if (!$result) {
            return null;
        }

        $maybe_rule_check_names = $maybe_rule->get_check_names_joined();

        foreach ($result as $parts) {
            $real_rule = $parts['real_rule'];
            $check_names = $parts['check_names'];

            if (!$real_rule instanceof Rule) {
                throw new \Exception('Non-rule returned from get_rule');
            }

            if ($maybe_rule_check_names === $check_names) {
                return $real_rule;
            }
        }

        return null;
    }

    // /**
    //  * @return Rule[] Returns an array of Rule objects
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
    public function findOneBySomeField($value): ?Rule
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
