<?php

namespace App\Entity;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\PropertyScanUrl")
     * @ORM\JoinColumn(nullable=false)
     */
    private $propertyScanUrlId;

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

    public function getPropertyScanUrlId(): ?PropertyScanUrl
    {
        return $this->propertyScanUrlId;
    }

    public function setPropertyScanUrlId(?PropertyScanUrl $propertyScanUrlId): self
    {
        $this->propertyScanUrlId = $propertyScanUrlId;

        return $this;
    }
}
