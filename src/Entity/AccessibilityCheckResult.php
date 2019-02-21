<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccessibilityCheckResultRepository")
 */
class AccessibilityCheckResult
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AccessibilityCheckVersion")
     * @ORM\JoinColumn(nullable=false)
     */
    private $accessibilityCheckVersion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AccessibilityCheckResultRelatedNode", mappedBy="accessibilityCheckResult", orphanRemoval=true)
     */
    private $relatedNodes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ScanUrl", inversedBy="accessibilityCheckResults")
     * @ORM\JoinColumn(nullable=false)
     */
    private $scanUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\AccessibilityTag")
     */
    private $tags;

    public function __construct()
    {
        $this->relatedNodes = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccessibilityCheckVersion(): ?AccessibilityCheckVersion
    {
        return $this->accessibilityCheckVersion;
    }

    public function setAccessibilityCheckVersion(?AccessibilityCheckVersion $accessibilityCheckVersion): self
    {
        $this->accessibilityCheckVersion = $accessibilityCheckVersion;

        return $this;
    }

    /**
     * @return Collection|AccessibilityCheckResultRelatedNode[]
     */
    public function getRelatedNodes(): Collection
    {
        return $this->relatedNodes;
    }

    public function addRelatedNode(AccessibilityCheckResultRelatedNode $relatedNode): self
    {
        if (!$this->relatedNodes->contains($relatedNode)) {
            $this->relatedNodes[] = $relatedNode;
            $relatedNode->setAccessibilityCheckResult($this);
        }

        return $this;
    }

    public function removeRelatedNode(AccessibilityCheckResultRelatedNode $relatedNode): self
    {
        if ($this->relatedNodes->contains($relatedNode)) {
            $this->relatedNodes->removeElement($relatedNode);
            // set the owning side to null (unless already changed)
            if ($relatedNode->getAccessibilityCheckResult() === $this) {
                $relatedNode->setAccessibilityCheckResult(null);
            }
        }

        return $this;
    }

    public function getScanUrl(): ?ScanUrl
    {
        return $this->scanUrl;
    }

    public function setScanUrl(?ScanUrl $scanUrl): self
    {
        $this->scanUrl = $scanUrl;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|AccessibilityTag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(AccessibilityTag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(AccessibilityTag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }
}
