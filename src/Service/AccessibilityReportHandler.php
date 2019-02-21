<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\AccessibilityCheck;
use App\Entity\AccessibilityCheckVersion;
use App\Entity\AccessibilityCheckResult;
use App\Entity\AccessibilityCheckResultRelatedNode;
use App\Entity\AccessibilityTag;
use App\Repository\ScanUrlRepository;
use App\Repository\AccessibilityCheckRepository;
use App\Repository\AccessibilityCheckVersionRepository;
use App\Repository\AccessibilityTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class AccessibilityReportHandler
{
    private $accessibilityCheckRepository;
    private $accessibilityCheckVersionRepository;
    private $accessibilityTagRepository;
    private $scanUrlRepository;
    private $entityManager;

    public function __construct(AccessibilityCheckRepository $accessibilityCheckRepository, AccessibilityCheckVersionRepository $accessibilityCheckVersionRepository, ScanUrlRepository $scanUrlRepository, EntityManagerInterface $entityManager, AccessibilityTagRepository $accessibilityTagRepository)
    {
        $this->accessibilityCheckRepository = $accessibilityCheckRepository;
        $this->accessibilityCheckVersionRepository = $accessibilityCheckVersionRepository;
        $this->accessibilityTagRepository = $accessibilityTagRepository;
        $this->scanUrlRepository = $scanUrlRepository;
        $this->entityManager = $entityManager;
    }

    public function get_report_for_single_scan_url(int $scanUrlId)
    {
        $scanUrl = $this->scanUrlRepository->find($scanUrlId);
        if(!$scanUrl){
            throw new \Exception('Could not find ScanUrl object by supplied Id: ' . $obj->scanUrlId);
        }

        $query = $this
                ->scanUrlRepository
                ->createQueryBuilder('su')
                ->leftJoin('su.accessibilityCheckResults', 'acr')
                ->leftJoin('acr.accessibilityCheckVersion', 'acv')
                ->leftJoin('acv.accessibilityCheck', 'ac')
                ->innerJoin('acr.tags', 'tags')
                ->select('
                            su.id,
                            su.url,
                            acv.message,
                            acv.impact,
                            ac.name,
                            tags.name as tag_name'
                        )
                ->andWhere('su.id = :scanUrlId')
                ->setParameter('scanUrlId', $scanUrl->getId())
                ->setMaxResults(10)
                ->getQuery()
            ;

            // dd($query->getSql());

            return $query->getResult();

        
        /*
SELECT
    su.id,
    su.url,
    acv.message,
    acv.impact,
    ac.name
FROM
    scan_url su
LEFT JOIN
    accessibility_check_result acr
ON
    su.id = acr.scan_url_id
LEFT JOIN
    accessibility_check_version acv
ON
    acv.id = acr.accessibility_check_version_id
LEFT JOIN
    accessibility_check ac
ON
    ac.id = acv.accessibility_check_id
WHERE
    su.id = 1
LIMIT 10;

        */
    }

    public function handle_report($obj)
    {
        if(!property_exists($obj, 'subUrlRequestStatus')){
            throw new \Exception('Could not find property subUrlRequestStatus');
        }

        if(!property_exists($obj, 'scanUrlId')){
            throw new \Exception('Could not find property scanUrlId');
        }

        $scanUrl = $this->scanUrlRepository->find((int) $obj->scanUrlId);
        if(!$scanUrl){
            throw new \Exception('Could not find ScanUrl object by supplied Id: ' . $obj->scanUrlId);
        }

        $sections = ['violations', 'passes', 'incomplete', 'inapplicable'];
        $node_cats = ['any', 'all', 'none'];

        foreach($sections as $section_name){
            if(!property_exists($obj->subUrlRequestStatus, $section_name)){
                continue;
            }

            $current_rules = $obj->subUrlRequestStatus->$section_name;
            foreach($current_rules as $current_rule){

                $tags = $this->get_or_create_tags($current_rule);
    
                if(!property_exists($current_rule, 'nodes')){
                    continue;
                }
    
                $nodes = $current_rule->nodes;
    
                if(!is_array($nodes) || 0 === count($nodes)){
                    continue;
                }
    
                foreach($nodes as $node){

                    foreach($node_cats as $node_cat_name){
                        if(!property_exists($node, $node_cat_name)){
                            continue;
                        }

                        $node_cat = $node->$node_cat_name;

                        if(!is_array($node_cat) || 0 === count($node_cat)){
                            continue;
                        }

                        foreach($node_cat as $check){
                            if(!property_exists($check, 'id')){
                                throw new \Exception('Check is missing property: id');
                            }

                            $check_version = $this->get_or_create_check_version($check);

                            if(0 === count($check->relatedNodes)){
                                continue;
                            }

                            $acr = new AccessibilityCheckResult();
                            $acr->setScanUrl($scanUrl);
                            $acr->setAccessibilityCheckVersion($check_version);
                            $acr->setCategory($section_name);
                            foreach($tags as $tag){
                                $acr->addTag($tag);
                            }

                            foreach($check->relatedNodes as $related_node){
                                $nr = new AccessibilityCheckResultRelatedNode();
                                $nr->setHtml($related_node->html);
                                $nr->setTargets($related_node->target);
                                $this->entityManager->persist($nr);
                                $acr->addRelatedNode($nr);
                            }

                            $this->entityManager->persist($acr);
                            $this->entityManager->flush();

                        }
                    }
                }


            }
           
        }
    }

    protected function get_or_create_tags($obj) : array
    {
        static $tags = [];

        $ret = [];
        if(0 === count($obj->tags)){
            return $ret;
        }

        foreach($obj->tags as $tag){
            if(array_key_exists($tag, $tags)){
                $ret[] = $tags[$tag];
                continue;
            }

            $maybe_tag = $this->accessibilityTagRepository->findOneBy(['name' => $tag]);
            if($maybe_tag){
                $tags[$tag] = $maybe_tag;
                $ret[] = $maybe_tag;
                continue;
            }

            $db_tag = new AccessibilityTag();
            $db_tag->setName($tag);
            $this->entityManager->persist($db_tag);
            $this->entityManager->flush();

            $tags[$tag] = $db_tag;
            $ret[] = $db_tag;
        }

        return $ret;
    }

    protected function get_or_create_check_version($check) : AccessibilityCheckVersion
    {
        $hash = $this->create_key_for_check_version($check);
        static $found_check_versions = [];

        if(!array_key_exists($check->id, $found_check_versions)){
            $found_check_versions[$check->id] = [];
        }

        if(array_key_exists($hash, $found_check_versions[$check->id])){
            return $found_check_versions[$check->id][$hash];
        }

        $existing = $this->accessibilityCheckVersionRepository->findOneBy(['impact' => $check->impact, 'message' => ($check->message ?? '')]);
        if($existing){
            $found_check_ids[$check->id][$hash] = $existing;
            return $existing;
        }

        $base_check = $this->get_or_create_check($check);

        $new_check = new AccessibilityCheckVersion();
        $new_check->setMessage($check->message ?? '');
        $new_check->setImpact($check->impact);
        $new_check->setAccessibilityCheck($base_check);
        
        $this->entityManager->persist($new_check);
        $this->entityManager->flush();

        $found_check_ids[$check->id][$hash] = $new_check;
        return $new_check;

    }

    protected function create_key_for_check_version($check) : string
    {
        return $check->impact . ':' . ($check->message ?? '');
    }

    protected function get_or_create_check($check) : AccessibilityCheck
    {
        static $found_check_ids = [];
        if(array_key_exists($check->id, $found_check_ids)){
            return $found_check_ids[$check->id];
        }

        $existing_check_from_db = $this->accessibilityCheckRepository->findOneBy(['name' => $check->id]);
        if($existing_check_from_db){
            $found_check_ids[$check->id] = $existing_check_from_db;
            return $existing_check_from_db;
        }

        $new_check = new AccessibilityCheck();
        $new_check->setName($check->id);
        
        $this->entityManager->persist($new_check);
        $this->entityManager->flush();

        $found_check_ids[$check->id] = $new_check;
        return $new_check;
    }
}