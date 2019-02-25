<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\aXe\RuleResultNode;
use App\Entity\aXe\RuleResultNodeDetails\RuleResultNodeDetailBase;
use App\Entity\aXe\RuleResults\RuleResultBase;
use App\Entity\aXe\ScanResult;
use App\Entity\ScanUrl;
use App\Repository\aXe\SharedStringRepository;
use App\Repository\aXe\TagRepository;
use App\Repository\ScanUrlRepository;
use Doctrine\ORM\EntityManagerInterface;

class AccessibilityReportHandler
{
    private $scanUrlRepository;
    private $entityManager;

    private $tagRepository;

    public function __construct(
        ScanUrlRepository $scanUrlRepository,
        EntityManagerInterface $entityManager,
        TagRepository $tagRepository,
        SharedStringRepository $sharedStringRepository
        ) {
        $this->scanUrlRepository = $scanUrlRepository;
        $this->entityManager = $entityManager;

        $this->tagRepository = $tagRepository;
        $this->sharedStringRepository = $sharedStringRepository;
    }

    public function process_v2_tags_only($obj)
    {
        static $cache = [];
        if (!array_key_exists($obj->scanUrlId, $cache)) {
            $tags_as_strings = [];
            $result_type_names = ['violations', 'passes', 'incomplete', 'inapplicable'];
            foreach ($result_type_names as $result_type_name) {
                foreach ($obj->subUrlRequestStatus->$result_type_name as $rule_thing) {
                    if (!$rule_thing->nodes || 0 === count($rule_thing->nodes)) {
                        continue;
                    }

                    $tags_as_strings = array_merge($tags_as_strings, $rule_thing->tags);
                }
            }

            $tags_as_strings = array_values(array_unique($tags_as_strings));

            $tags_as_objects = $this->tagRepository->findAll();
            $dirty = false;

            $existing_tag_names = [];
            foreach ($tags_as_objects as $tag) {
                $existing_tag_names[] = $tag->getName();
            }

            foreach ($tags_as_strings as $tag_name) {
                if (!in_array($tag_name, $existing_tag_names)) {
                    $dirty = true;
                    $this->tagRepository->get_or_create_one($tag_name);
                }
            }

            if ($dirty) {
                $tags_as_objects = $this->tagRepository->findAll();
            }
            $cache[$obj->scanUrlId] = $tags_as_objects;
        }

        return $cache[$obj->scanUrlId];
    }

    protected function get_tags($obj, $rule_thing) : array
    {
        $all_tags = $this->process_v2_tags_only($obj);
        $tags = [];
        foreach ($rule_thing->tags as $tag) {
            foreach ($all_tags as $tag_obj) {
                if ($tag === $tag_obj->getName()) {
                    $tags[] = $tag_obj;
                    break;
                }
            }
        }

        return $tags;
    }

    protected function sanity_check_obj_and_get_scan_url($obj) : ScanUrl
    {
        if (!property_exists($obj, 'subUrlRequestStatus')) {
            throw new \Exception('Could not find property subUrlRequestStatus');
        }

        if (!property_exists($obj, 'scanUrlId')) {
            throw new \Exception('Could not find property scanUrlId');
        }

        $scanUrl = $this->scanUrlRepository->find((int) $obj->scanUrlId);
        if (!$scanUrl) {
            throw new \Exception('Could not find ScanUrl object by supplied Id: ' . $obj->scanUrlId);
        }

        return $scanUrl;
    }

    public function get_report_for_single_scan_url(int $scanUrlId)
    {
        $scanUrl = $this->scanUrlRepository->find($scanUrlId);
        if (!$scanUrl) {
            throw new \Exception('Could not find ScanUrl object by supplied Id: ' . $obj->scanUrlId);
        }

        $query = $this
                ->scanUrlRepository
                ->createQueryBuilder('su')
                ->leftJoin('su.accessibilityCheckResults', 'acr')
                ->leftJoin('acr.accessibilityCheckVersion', 'acv')
                ->leftJoin('acv.accessibilityCheck', 'ac')
                ->innerJoin('acr.tags', 'tags')
                ->select(
                    '
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
    }

    private function build_shared_string_cache($obj)
    {
        $result_type_names = ['violations', 'passes', 'incomplete', 'inapplicable'];
        foreach ($result_type_names as $result_type_name) {

            if (!property_exists($obj->subUrlRequestStatus, $result_type_name)) {
                continue;
            }

            foreach ($obj->subUrlRequestStatus->$result_type_name as $rule_thing) {
                if (!property_exists($rule_thing, 'nodes')) {
                    throw new \Exception('Rule missing node collection');
                }

                if (!$rule_thing->nodes || 0 === count($rule_thing->nodes)) {
                    continue;
                }
                foreach($rule_thing->nodes as $node){
                    if($node->html){
                        $this->sharedStringRepository->get_or_create_one($node->html, false);
                    }
                }
            }
        }

        $this->entityManager->flush();
    }

    public function process_v3($obj)
    {
        $scanUrl = $this->sanity_check_obj_and_get_scan_url($obj);

        $this->build_shared_string_cache($obj);

        $scan_result = new ScanResult();
        $scan_result->setScanUrl($scanUrl);

        $result_type_names = ['violations', 'passes', 'incomplete', 'inapplicable'];
        foreach ($result_type_names as $result_type_name) {

            if (!property_exists($obj->subUrlRequestStatus, $result_type_name)) {
                continue;
            }

            foreach ($obj->subUrlRequestStatus->$result_type_name as $rule_thing) {
                if (!property_exists($rule_thing, 'nodes')) {
                    throw new \Exception('Rule missing node collection');
                }

                if (!$rule_thing->nodes || 0 === count($rule_thing->nodes)) {
                    continue;
                }

                $rule_result = RuleResultBase::create_from_string($result_type_name);
                $rule_result->setName($rule_thing->id);
                $rule_result->setImpact($rule_thing->impact);
                $rule_result->setDescription($rule_thing->description);
                $rule_result->setHelp($rule_thing->help);
                $rule_result->addTags($this->get_tags($obj, $rule_thing));

                foreach($rule_thing->nodes as $node){
                    $rule_result_node = new RuleResultNode();

                    if($node->html){
                        $rule_result_node->setHtmlSharedString($this->sharedStringRepository->get_or_create_one($node->html));
                    }
                    
                    // $rule_result_node->setHtml($node->html);
                    $rule_result_node->setTarget($node->target);
                    if(property_exists($node, 'failureSummary')){
                        $rule_result_node->setFailureSummary($node->failureSummary);
                    }

                    $detail_types = ['any', 'all', 'none'];
                    foreach($detail_types as $detail_type){
                        if(!property_exists($node, $detail_type)){
                            continue;
                        }

                        foreach($node->$detail_type as $d){
                            $detail = RuleResultNodeDetailBase::create_from_string($detail_type);
                            $detail->setName($d->id);
                            if($d->data){
                                $g = json_decode(json_encode($d->data), true);
                                if(!is_array($g)){
                                    $g = [$g];
                                }
                                $detail->setData($g);
                            }
                            $detail->setRelatedNodes($d->relatedNodes);
                            $detail->setImpact($d->impact);
                            $detail->setMessage($d->message);
                            $this->entityManager->persist($detail);

                            $rule_result_node->addDetail($detail);
                        }

                        $this->entityManager->persist($rule_result_node);
                    }

                    $rule_result->addNode($rule_result_node);
                    $this->entityManager->persist($rule_result);
                }

                $scan_result->addResult($rule_result);
            }
        }

        $this->entityManager->persist($scan_result);
        $this->entityManager->flush();
    }
}
