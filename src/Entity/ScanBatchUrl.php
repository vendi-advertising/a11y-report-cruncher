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
     * @ORM\OneToOne(targetEntity="App\Entity\PropertyScanUrlLog", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $propertyScanUrlLog;

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

    public function getPropertyScanUrlLog(): ?PropertyScanUrlLog
    {
        return $this->propertyScanUrlLog;
    }

    public function setPropertyScanUrlLog(PropertyScanUrlLog $propertyScanUrlLog): self
    {
        $this->propertyScanUrlLog = $propertyScanUrlLog;

        return $this;
    }
}
