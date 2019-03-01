<?php

namespace App\Repository\aXe;

use App\Entity\aXe\SharedString;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SharedString|null find($id, $lockMode = null, $lockVersion = null)
 * @method SharedString|null findOneBy(array $criteria, array $orderBy = null)
 * @method SharedString[]    findAll()
 * @method SharedString[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SharedStringRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SharedString::class);
    }

    final public function get_or_create_one(?string $value, bool $flush = true)
    {
        if(!$value){
            return null;
        }
        
        $cache_prefix = \mb_strtolower(\str_replace('\\', '_', $this->getClassName()));
        $cache_version = 'v2';
        $cache_key = hash('sha256', "{$cache_prefix}.{$cache_version}.{$value}");

        //Store for fast access
        static $cache_by_key = [];

        //Check the static cache first
        if (array_key_exists($cache_key, $cache_by_key)) {
            $item = $cache_by_key[$cache_key];
            return $item;
        }

        //Check the database next
        $existing = $this->findOneBy(['value' => $value]);
        if ($existing) {
            $cache_by_key[$cache_key] = $existing;
            // $this->cache->set($cache_key, $existing);
            return $existing;
        }

        $data['value'] = $value;

        //Lastly, create a new one
        $new = new SharedString();
        $new->setValue($value);
        $this->getEntityManager()->persist($new);
        if($flush){
            $this->getEntityManager()->flush();
        }
        $cache_by_key[$cache_key] = $new;
        // $this->cache->set($cache_key, $new);
        return $new;
    }
}
