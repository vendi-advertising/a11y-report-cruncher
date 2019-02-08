<?php

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
    private $propertyId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateTimeCreated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PropertyScanUrl", mappedBy="propertyScanId", orphanRemoval=true)
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

    public function getPropertyId(): ?Property
    {
        return $this->propertyId;
    }

    public function setPropertyId(?Property $propertyId): self
    {
        $this->propertyId = $propertyId;

        return $this;
    }

    public function getDateTimeCreated(): ?\DateTimeInterface
    {
        return $this->DateTimeCreated;
    }

    public function setDateTimeCreated(\DateTimeInterface $DateTimeCreated): self
    {
        $this->DateTimeCreated = $DateTimeCreated;

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
            $propertyScanUrl->setPropertyScanId($this);
        }

        return $this;
    }

    public function removePropertyScanUrl(PropertyScanUrl $propertyScanUrl): self
    {
        if ($this->propertyScanUrls->contains($propertyScanUrl)) {
            $this->propertyScanUrls->removeElement($propertyScanUrl);
            // set the owning side to null (unless already changed)
            if ($propertyScanUrl->getPropertyScanId() === $this) {
                $propertyScanUrl->setPropertyScanId(null);
            }
        }

        return $this;
    }
}
