<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\aXe\ScanResult;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScanUrlRepository")
 */
class ScanUrl
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Scan", inversedBy="scanUrls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $scan;

    /**
     * @ORM\Column(type="string", length=2048)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contentType;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $byteSize;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $httpStatus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $scanStatus;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\aXe\ScanResult", mappedBy="scanUrl", cascade={"persist", "remove"})
     */
    private $scanResult;

    public const SCAN_STATUS_READY = 'SCAN_STATUS_READY';

    public const SCAN_STATUS_SUCCESS = 'SCAN_STATUS_SUCCESS';

    public const SCAN_STATUS_ERROR = 'SCAN_STATUS_ERROR';

    public function __construct()
    {
        $this->scanStatus = self::SCAN_STATUS_READY;
        $this->accessibilityCheckResults = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScan(): ?Scan
    {
        return $this->scan;
    }

    public function setScan(?Scan $scan): self
    {
        $this->scan = $scan;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function setContentType(?string $contentType): self
    {
        $this->contentType = $contentType;

        return $this;
    }

    public function getByteSize(): ?int
    {
        return $this->byteSize;
    }

    public function setByteSize(?int $byteSize): self
    {
        $this->byteSize = $byteSize;

        return $this;
    }

    public function getHttpStatus(): ?int
    {
        return $this->httpStatus;
    }

    public function setHttpStatus(?int $httpStatus): self
    {
        $this->httpStatus = $httpStatus;

        return $this;
    }

    public function getScanStatus(): ?string
    {
        return $this->scanStatus;
    }

    public function setScanStatus(?string $scanStatus): self
    {
        $this->scanStatus = $scanStatus;

        return $this;
    }

    public function getScanResult(): ?ScanResult
    {
        return $this->scanResult;
    }

    public function setScanResult(ScanResult $scanResult): self
    {
        $this->scanResult = $scanResult;

        // set the owning side of the relation if necessary
        if ($this !== $scanResult->getScanUrl()) {
            $scanResult->setScanUrl($this);
        }

        return $this;
    }
}
