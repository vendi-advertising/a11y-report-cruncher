<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScanBatchUrlRepository")
 */
class ScanBatchUrl
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ScanBatch", inversedBy="scanBatchUrls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $scanBatchId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PropertyScanUrl")
     * @ORM\JoinColumn(nullable=false)
     */
    private $propertyScanUrlId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScanBatchId(): ?ScanBatch
    {
        return $this->scanBatchId;
    }

    public function setScanBatchId(?ScanBatch $scanBatchId): self
    {
        $this->scanBatchId = $scanBatchId;

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
