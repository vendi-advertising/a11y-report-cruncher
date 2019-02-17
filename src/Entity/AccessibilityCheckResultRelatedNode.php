<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccessibilityCheckResultRelatedNodeRepository")
 */
class AccessibilityCheckResultRelatedNode
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $html;

    /**
     * @ORM\Column(type="simple_array")
     */
    private $targets = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AccessibilityCheckResult", inversedBy="relatedNodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $accessibilityCheckResult;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTargets(): ?array
    {
        return $this->targets;
    }

    public function setTargets(array $targets): self
    {
        $this->targets = $targets;

        return $this;
    }

    public function getAccessibilityCheckResult(): ?AccessibilityCheckResult
    {
        return $this->accessibilityCheckResult;
    }

    public function setAccessibilityCheckResult(?AccessibilityCheckResult $accessibilityCheckResult): self
    {
        $this->accessibilityCheckResult = $accessibilityCheckResult;

        return $this;
    }
}
