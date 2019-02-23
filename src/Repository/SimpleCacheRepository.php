<?php

namespace App\Repository;

use Psr\SimpleCache\CacheInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

abstract class SimpleCacheRepository extends ServiceEntityRepository
{
    protected $cache;

    public function __construct(RegistryInterface $registry, $entityClass, CacheInterface $cache)
    {
        parent::__construct($registry, $entityClass);
        $this->cache = $cache;
    }

    final public function get_or_create_many(array $names) : array
    {
        $ret = [];
        foreach($names as $name){
            $ret[] = $this->get_or_create_one($name);
        }
        return $ret;
    }

    final public function get_or_create_one(string $name, $data = [])
    {
        $cache_prefix = \mb_strtolower(\str_replace('\\', '_', $this->getClassName()));
        $cache_version = 'v2';
        $cache_key = "{$cache_prefix}.{$cache_version}.{$name}";

        //Store for fast access
        static $cache_by_key = [];

        //Check the static cache first
        if(array_key_exists($cache_key, $cache_by_key)){
            $item = $cache_by_key[$cache_key];
            return $item;
        }

        if(method_exists($this, 'findOneByNameCached')){
            $item = $this->findOneByNameCached($name);
            if($item){
                return $item;
            }
        }

        // //Check the PSR cache second
        // if ($this->cache->has($cache_key)) {
        //     $item = $this->cache->get($cache_key);
        //     // dd($item);
        //     // return $item;
        //     // $cache_by_key[$cache_key] = $item;
        //     // return $this->find((int)$item->getId());
        // }

        //Check the database third
        $existing = $this->findOneBy(['name' => $name]);
        if($existing){
            $cache_by_key[$cache_key] = $existing;
            // $this->cache->set($cache_key, $existing);
            return $existing;
        }

        $data['name'] = $name;

        //Lastly, create a new one
        $new = $this->create_new();
        $this->set_properties_on_new($new, $data);
        $this->getEntityManager()->persist($new);
        $this->getEntityManager()->flush();
        $cache_by_key[$cache_key] = $new;
        // $this->cache->set($cache_key, $new);
        return $new;
    }

    public function create_new()
    {
        $class_name = $this->getClassName();
        $new = new $class_name;

        return $new;
    }

    public function set_properties_on_new($obj, $props)
    {
        $obj->setName($props['name']);
    }

    // /**
    //  * @return Tag[] Returns an array of Tag objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tag
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
