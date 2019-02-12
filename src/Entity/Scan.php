<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScanRepository")
 */
class Scan
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Property", inversedBy="scans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $property;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $scanType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ScanUrl", mappedBy="scan", orphanRemoval=true)
     */
    private $scanUrls;

    public function __construct()
    {
        $this->scanUrls = new ArrayCollection();
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

    public function getScanType(): ?string
    {
        return $this->scanType;
    }

    public function setScanType(string $scanType): self
    {
        $this->scanType = $scanType;

        return $this;
    }

    /**
     * @return Collection|ScanUrl[]
     */
    public function getScanUrls(): Collection
    {
        return $this->scanUrls;
    }

    public function addScanUrl(ScanUrl $scanUrl): self
    {
        if (!$this->scanUrls->contains($scanUrl)) {
            $this->scanUrls[] = $scanUrl;
            $scanUrl->setScan($this);
        }

        return $this;
    }

    public function removeScanUrl(ScanUrl $scanUrl): self
    {
        if ($this->scanUrls->contains($scanUrl)) {
            $this->scanUrls->removeElement($scanUrl);
            // set the owning side to null (unless already changed)
            if ($scanUrl->getScan() === $this) {
                $scanUrl->setScan(null);
            }
        }

        return $this;
    }
}
