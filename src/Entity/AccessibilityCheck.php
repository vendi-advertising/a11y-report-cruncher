<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccessibilityCheckRepository")
 */
class AccessibilityCheck
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
     * @ORM\Column(type="datetime")
     */
    private $dateTimeCreated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AccessibilityCheckVersion", mappedBy="accessibilityCheck", orphanRemoval=true)
     */
    private $accessibilityCheckVersions;

    public function __construct()
    {
        $this->dateTimeCreated = new \DateTime();
        $this->accessibilityCheckVersions = new ArrayCollection();
    }

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

    public function getDateTimeCreated(): ?\DateTimeInterface
    {
        return $this->dateTimeCreated;
    }

    public function setDateTimeCreated(\DateTimeInterface $dateTimeCreated): self
    {
        $this->dateTimeCreated = $dateTimeCreated;

        return $this;
    }

    /**
     * @return Collection|AccessibilityCheckVersion[]
     */
    public function getAccessibilityCheckVersions(): Collection
    {
        return $this->accessibilityCheckVersions;
    }

    public function addAccessibilityCheckVersion(AccessibilityCheckVersion $accessibilityCheckVersion): self
    {
        if (!$this->accessibilityCheckVersions->contains($accessibilityCheckVersion)) {
            $this->accessibilityCheckVersions[] = $accessibilityCheckVersion;
            $accessibilityCheckVersion->setAccessibilityCheck($this);
        }

        return $this;
    }

    public function removeAccessibilityCheckVersion(AccessibilityCheckVersion $accessibilityCheckVersion): self
    {
        if ($this->accessibilityCheckVersions->contains($accessibilityCheckVersion)) {
            $this->accessibilityCheckVersions->removeElement($accessibilityCheckVersion);
            // set the owning side to null (unless already changed)
            if ($accessibilityCheckVersion->getAccessibilityCheck() === $this) {
                $accessibilityCheckVersion->setAccessibilityCheck(null);
            }
        }

        return $this;
    }
}
