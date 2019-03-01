<?php

declare(strict_types=1);

namespace App\Entity\aXe\RuleResultNodeDetails;

use App\Entity\AXe\RuleResultNode;
use App\Entity\aXe\SharedString;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"any" = "AnyRuleResultNodeDetail", "all" = "AllRuleResultNodeDetail", "none" = "NoneRuleResultNodeDetail"})
 * @ORM\Table(name="axe_result_node_detail")
 */
abstract class RuleResultNodeDetailBase
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $data = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $relatedNodes = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $impact;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AXe\RuleResultNode", inversedBy="details")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ruleResultNode;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\aXe\SharedString")
     */
    private $messageSharedString;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getRelatedNodes(): ?array
    {
        return $this->relatedNodes;
    }

    public function setRelatedNodes(?array $relatedNodes): self
    {
        $this->relatedNodes = $relatedNodes;

        return $this;
    }

    public function getImpact(): ?string
    {
        return $this->impact;
    }

    public function setImpact(?string $impact): self
    {
        $this->impact = $impact;

        return $this;
    }

    public static function create_from_string(string $string) : self
    {
        switch($string){
            case 'any':
                return self::create_any();
            case 'all':
                return self::create_all();
            case 'none':
                return self::create_none();
        }

        throw new \Exception('Unknown value passed to create_from_string():' . $string);
    }

    public static function create_any() : AnyRuleResultNodeDetail
    {
        return new AnyRuleResultNodeDetail();
    }

    public static function create_all() : AllRuleResultNodeDetail
    {
        return new AllRuleResultNodeDetail();
    }

    public static function create_none() : NoneRuleResultNodeDetail
    {
        return new NoneRuleResultNodeDetail();
    }

    public function getRuleResultNode(): ?RuleResultNode
    {
        return $this->ruleResultNode;
    }

    public function setRuleResultNode(?RuleResultNode $ruleResultNode): self
    {
        $this->ruleResultNode = $ruleResultNode;

        return $this;
    }

    public function getMessageSharedString(): ?SharedString
    {
        return $this->messageSharedString;
    }

    public function setMessageSharedString(?SharedString $messageSharedString): self
    {
        $this->messageSharedString = $messageSharedString;

        return $this;
    }
}
