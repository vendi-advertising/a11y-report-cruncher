<?php

declare(strict_types=1);

namespace App\Entity\aXe\RuleResults;

use App\Entity\AXe\ScanResult;
use App\Entity\aXe\RuleResultNode;
use App\Entity\aXe\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"violations" = "ViolationRuleResult", "passes" = "PassRuleResult", "incomplete" = "IncompleteRuleResult", "inapplicable" = "InapplicableRuleResult"})
 * @ORM\Table(name="axe_result")
 */
abstract class RuleResultBase
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $impact;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\aXe\Tag")
     * @ORM\JoinTable(name="axe_results_tags")
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $help;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\aXe\RuleResultNode", mappedBy="ruleResultBase", orphanRemoval=true)
     */
    private $nodes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AXe\ScanResult", inversedBy="results")
     * @ORM\JoinColumn(nullable=false)
     */
    private $scanResult;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->nodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function addTags(Iterable $tags): self
    {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getHelp(): ?string
    {
        return $this->help;
    }

    public function setHelp(?string $help): self
    {
        $this->help = $help;

        return $this;
    }

    public static function create_from_string(string $string) : self
    {
        switch($string){
            case 'violations':
                return self::create_violation();
            case 'passes':
                return self::create_pass();
            case 'incomplete':
                return self::create_incomplete();
            case 'inapplicable':
                return self::create_inapplicable();
        }

        throw new \Exception('Unknown value passed to create_from_string():' . $string);
    }

    public static function create_violation() : ViolationRuleResult
    {
        return new ViolationRuleResult();
    }

    public static function create_pass() : PassRuleResult
    {
        return new PassRuleResult();
    }

    public static function create_incomplete() : IncompleteRuleResult
    {
        return new IncompleteRuleResult();
    }

    public static function create_inapplicable() : InapplicableRuleResult
    {
        return new InapplicableRuleResult();
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

    /**
     * @return Collection|RuleResultNode[]
     */
    public function getNodes(): Collection
    {
        return $this->nodes;
    }

    public function addNode(RuleResultNode $node): self
    {
        if (!$this->nodes->contains($node)) {
            $this->nodes[] = $node;
            $node->setRuleResultBase($this);
        }

        return $this;
    }

    public function removeNode(RuleResultNode $node): self
    {
        if ($this->nodes->contains($node)) {
            $this->nodes->removeElement($node);
            // set the owning side to null (unless already changed)
            if ($node->getRuleResultBase() === $this) {
                $node->setRuleResultBase(null);
            }
        }

        return $this;
    }

    public function getScanResult(): ?ScanResult
    {
        return $this->scanResult;
    }

    public function setScanResult(?ScanResult $scanResult): self
    {
        $this->scanResult = $scanResult;

        return $this;
    }
}
