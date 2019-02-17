<?php

namespace App\Entity;

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
}
