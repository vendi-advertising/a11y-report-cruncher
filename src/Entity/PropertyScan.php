<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropertyScanRepository")
 */
class PropertyScan
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Property", inversedBy="propertyScans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $property;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTimeCreated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PropertyScanUrl", mappedBy="propertyScan", orphanRemoval=true)
     */
    private $propertyScanUrls;

    public function __construct()
    {
        $this->propertyScanUrls = new ArrayCollection();
        $this->dateTimeCreated = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): self
    {
        $this->property = $property;

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
     * @return Collection|PropertyScanUrl[]
     */
    public function getPropertyScanUrls(): Collection
    {
        return $this->propertyScanUrls;
    }

    public function addPropertyScanUrl(PropertyScanUrl $propertyScanUrl): self
    {
        if (!$this->propertyScanUrls->contains($propertyScanUrl)) {
            $this->propertyScanUrls[] = $propertyScanUrl;
            $propertyScanUrl->setPropertyScan($this);
        }

        return $this;
    }

    public function removePropertyScanUrl(PropertyScanUrl $propertyScanUrl): self
    {
        if ($this->propertyScanUrls->contains($propertyScanUrl)) {
            $this->propertyScanUrls->removeElement($propertyScanUrl);
            // set the owning side to null (unless already changed)
            if ($propertyScanUrl->getPropertyScan() === $this) {
                $propertyScanUrl->setPropertyScan(null);
            }
        }

        return $this;
    }
}
