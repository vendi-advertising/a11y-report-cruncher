<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\aXe\Rule;
use App\Repository\aXe\CheckRepository;
use App\Repository\aXe\ResultTypeRepository;
use App\Repository\aXe\RuleRepository;
use App\Repository\aXe\TagRepository;
use App\Repository\ScanUrlRepository;
use Doctrine\ORM\EntityManagerInterface;

class AccessibilityReportHandler
{
    private $scanUrlRepository;
    private $entityManager;

    private $resultTypeRepository;
    private $tagRepository;
    private $checkRepository;
    private $ruleRepository;

    public function __construct(
        ScanUrlRepository $scanUrlRepository,
        EntityManagerInterface $entityManager,
        ResultTypeRepository $resultTypeRepository,
        TagRepository $tagRepository,
        CheckRepository $checkRepository,
        RuleRepository $ruleRepository
        ) {
        $this->scanUrlRepository = $scanUrlRepository;
        $this->entityManager = $entityManager;

        $this->resultTypeRepository = $resultTypeRepository;
        $this->tagRepository = $tagRepository;
        $this->checkRepository = $checkRepository;
        $this->ruleRepository = $ruleRepository;
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

    protected function get_checks($obj, $node) : array
    {
        $all_checks = $this->process_v2_checks_only($obj);

        $check_types = [
            'any',
            'all',
            'none',
        ];

        $ret = [];
        foreach ($check_types as $check_type_name) {
            $check_things = $node->$check_type_name;
            if (0===count($check_things)) {
                continue;
            }

            foreach ($check_things as $check_thing) {
                foreach ($all_checks as $check_obj) {
                    if ($check_obj->getName() === $check_thing->id) {
                        $ret[] = $check_obj;
                    }
                }
            }
        }

        return $ret;
    }

    public function process_v2_checks_only($obj)
    {
        static $cache = [];
        if (!array_key_exists($obj->scanUrlId, $cache)) {
            $checks_as_strings = [];
            $result_type_names = ['violations', 'passes', 'incomplete', 'inapplicable'];
            foreach ($result_type_names as $result_type_name) {
                foreach ($obj->subUrlRequestStatus->$result_type_name as $rule_thing) {
                    if (!$rule_thing->nodes || 0 === count($rule_thing->nodes)) {
                        continue;
                    }
    
                    $node = reset($rule_thing->nodes);
    
                    $check_types = [
                        'any',
                        'all',
                        'none',
                    ];
                    foreach ($check_types as $check_type_name) {
                        $check_things = $node->$check_type_name;
                        if (0===count($check_things)) {
                            continue;
                        }
    
                        foreach ($check_things as $check_thing) {
                            if (!array_key_exists($check_thing->id, $checks_as_strings)) {
                                $checks_as_strings[$check_thing->id] = [
                                    'impact' => $check_thing->impact,
                                    'type' => $check_type_name,
                                ];
                            }
                        }
                    }
                }
            }
    
            ksort($checks_as_strings);
            
            $checks_as_objects = $this->checkRepository->findAll();
            $dirty = false;
            $existing_check_names = [];
            foreach ($checks_as_objects as $check) {
                $existing_check_names[] = $check->getName();
            }
    
            foreach ($checks_as_strings as $check_name => $data) {
                if (!in_array($check_name, $existing_check_names)) {
                    $dirty = true;
                    $this->checkRepository->get_or_create_one($check_name, $data);
                }
            }
    
            if ($dirty) {
                $checks_as_objects = $this->checkRepository->findAll();
            }

            $cache[$obj->scanUrlId] = $checks_as_objects;
        }

        return $cache[$obj->scanUrlId];
    }

    public function process_v2($obj)
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

        $result_type_names = ['violations', 'passes', 'incomplete', 'inapplicable'];
        foreach ($result_type_names as $result_type_name) {
            // $result_type = $this->resultTypeRepository->get_or_create_one($result_type_name);

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

                $node = reset($rule_thing->nodes);

                $rule = new Rule();
                $rule->addTags($this->get_tags($obj, $rule_thing));
                $rule->setImpact($rule_thing->impact);
                $rule->setName($rule_thing->id);
                $rule->setDescription($rule_thing->description);
                $rule->setHelp($rule_thing->help);
                $rule->addChecks($this->get_checks($obj, $node));

                $real_rule = $this->ruleRepository->get_rule($rule);

                if (!$real_rule) {
                    $this->entityManager->persist($rule);
                    $this->entityManager->flush();
                    $real_rule = $rule;
                }

                //Work with $real_rule here
            }
        }
        
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
}
