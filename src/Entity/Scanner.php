<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScannerRepository")
 */
class Scanner
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ScannerType", inversedBy="scanners")
     * @ORM\JoinColumn(nullable=false)
     */
    private $scannerType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTimeCreated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PropertyScanUrlLog", mappedBy="scannerId", orphanRemoval=true)
     */
    private $propertyScanUrlLogs;

    public function __construct()
    {
        $this->propertyScanUrlLogs = new ArrayCollection();
        $this->dateTimeCreated = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScannerType(): ?ScannerType
    {
        return $this->scannerType;
    }

    public function setScannerType(?ScannerType $scannerType): self
    {
        $this->scannerType = $scannerType;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return Collection|PropertyScanUrlLog[]
     */
    public function getPropertyScanUrlLogs(): Collection
    {
        return $this->propertyScanUrlLogs;
    }

    public function addPropertyScanUrlLog(PropertyScanUrlLog $propertyScanUrlLog): self
    {
        if (!$this->propertyScanUrlLogs->contains($propertyScanUrlLog)) {
            $this->propertyScanUrlLogs[] = $propertyScanUrlLog;
            $propertyScanUrlLog->setScannerId($this);
        }

        return $this;
    }

    public function removePropertyScanUrlLog(PropertyScanUrlLog $propertyScanUrlLog): self
    {
        if ($this->propertyScanUrlLogs->contains($propertyScanUrlLog)) {
            $this->propertyScanUrlLogs->removeElement($propertyScanUrlLog);
            // set the owning side to null (unless already changed)
            if ($propertyScanUrlLog->getScannerId() === $this) {
                $propertyScanUrlLog->setScannerId(null);
            }
        }

        return $this;
    }
}
