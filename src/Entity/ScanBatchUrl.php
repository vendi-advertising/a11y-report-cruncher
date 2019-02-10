<?php

declare(strict_types=1);

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
    private $scanBatch;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PropertyScanUrl")
     * @ORM\JoinColumn(nullable=false)
     */
    private $propertyScanUrl;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScanBatch(): ?ScanBatch
    {
        return $this->scanBatch;
    }

    public function setScanBatch(?ScanBatch $scanBatch): self
    {
        $this->scanBatch = $scanBatch;

        return $this;
    }

    public function getPropertyScanUrl(): ?PropertyScanUrl
    {
        return $this->propertyScanUrl;
    }

    public function setPropertyScanUrl(?PropertyScanUrl $propertyScanUrl): self
    {
        $this->propertyScanUrl = $propertyScanUrl;

        return $this;
    }
}
