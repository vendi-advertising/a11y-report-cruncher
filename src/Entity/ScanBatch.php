<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScanBatchRepository")
 */
class ScanBatch
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Scanner")
     * @ORM\JoinColumn(nullable=false)
     */
    private $scannerId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTimeExpires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ScanBatchUrl", mappedBy="scanBatchId", orphanRemoval=true)
     */
    private $scanBatchUrls;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTimeCreated;

    public function __construct()
    {
        $this->scanBatchUrls = new ArrayCollection();
        $this->dateTimeCreated = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScannerId(): ?Scanner
    {
        return $this->scannerId;
    }

    public function setScannerId(?Scanner $scannerId): self
    {
        $this->scannerId = $scannerId;

        return $this;
    }

    public function getDateTimeExpires(): ?\DateTimeInterface
    {
        return $this->dateTimeExpires;
    }

    public function setDateTimeExpires(\DateTimeInterface $dateTimeExpires): self
    {
        $this->dateTimeExpires = $dateTimeExpires;

        return $this;
    }

    /**
     * @return Collection|ScanBatchUrl[]
     */
    public function getScanBatchUrls(): Collection
    {
        return $this->scanBatchUrls;
    }

    public function addScanBatchUrl(ScanBatchUrl $scanBatchUrl): self
    {
        if (!$this->scanBatchUrls->contains($scanBatchUrl)) {
            $this->scanBatchUrls[] = $scanBatchUrl;
            $scanBatchUrl->setScanBatchId($this);
        }

        return $this;
    }

    public function removeScanBatchUrl(ScanBatchUrl $scanBatchUrl): self
    {
        if ($this->scanBatchUrls->contains($scanBatchUrl)) {
            $this->scanBatchUrls->removeElement($scanBatchUrl);
            // set the owning side to null (unless already changed)
            if ($scanBatchUrl->getScanBatchId() === $this) {
                $scanBatchUrl->setScanBatchId(null);
            }
        }

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
}
