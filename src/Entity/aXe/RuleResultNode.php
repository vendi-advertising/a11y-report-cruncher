<?php

namespace App\Entity\aXe;

use App\Entity\AXe\RuleResults\RuleResultBase;
use App\Entity\aXe\RuleResultNodeDetails\RuleResultNodeDetailBase;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\aXe\RuleResultNodeRepository")
 * @ORM\Table(name="axe_result_node")
 */
class RuleResultNode
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $html;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $target = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $failureSummary;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\aXe\RuleResultNodeDetails\RuleResultNodeDetailBase", mappedBy="ruleResultNode", orphanRemoval=true)
     */
    private $details;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AXe\RuleResults\RuleResultBase", inversedBy="nodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ruleResultBase;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\aXe\SharedString")
     */
    private $htmlSharedString;

    public function __construct()
    {
        $this->details = new ArrayCollection();
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

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(?string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function getTarget(): ?array
    {
        return $this->target;
    }

    public function setTarget(?array $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getFailureSummary(): ?string
    {
        return $this->failureSummary;
    }

    public function setFailureSummary(?string $failureSummary): self
    {
        $this->failureSummary = $failureSummary;

        return $this;
    }

    /**
     * @return Collection|RuleResultNodeDetailBase[]
     */
    public function getDetails(): Collection
    {
        return $this->details;
    }

    public function addDetail(RuleResultNodeDetailBase $detail): self
    {
        if (!$this->details->contains($detail)) {
            $this->details[] = $detail;
            $detail->setRuleResultNode($this);
        }

        return $this;
    }

    public function removeDetail(RuleResultNodeDetailBase $detail): self
    {
        if ($this->details->contains($detail)) {
            $this->details->removeElement($detail);
            // set the owning side to null (unless already changed)
            if ($detail->getRuleResultNode() === $this) {
                $detail->setRuleResultNode(null);
            }
        }

        return $this;
    }

    public function getRuleResultBase(): ?RuleResultBase
    {
        return $this->ruleResultBase;
    }

    public function setRuleResultBase(?RuleResultBase $ruleResultBase): self
    {
        $this->ruleResultBase = $ruleResultBase;

        return $this;
    }

    public function getHtmlSharedString(): ?SharedString
    {
        return $this->htmlSharedString;
    }

    public function setHtmlSharedString(?SharedString $htmlSharedString): self
    {
        $this->htmlSharedString = $htmlSharedString;

        return $this;
    }
}
