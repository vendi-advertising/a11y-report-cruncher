<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropertyScanUrlLogRepository")
 */
class PropertyScanUrlLog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PropertyScanUrl", inversedBy="propertyScanUrlLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $propertyScanUrl;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Scanner", inversedBy="propertyScanUrlLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $scanner;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $entryDirection;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    public const DIRECTION_IN = 'check-in';

    public const DIRECTION_OUT = 'check-out';

    public static function get_entry_directions() : array
    {
        return [
            self::DIRECTION_IN,
            self::DIRECTION_OUT,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getScanner(): ?Scanner
    {
        return $this->scanner;
    }

    public function setScanner(?Scanner $scanner): self
    {
        $this->scanner = $scanner;

        return $this;
    }

    public function getEntryDirection(): ?string
    {
        return $this->entryDirection;
    }

    public function setEntryDirection(?string $entryDirection): self
    {
        $this->entryDirection = $entryDirection;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
