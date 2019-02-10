<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropertyScanUrlRepository")
 */
class PropertyScanUrl
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2048)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PropertyScan", inversedBy="propertyScanUrls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $propertyScan;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PropertyScanUrlLog", mappedBy="propertyScanUrl", orphanRemoval=true)
     */
    private $propertyScanUrlLogs;

    public function __construct()
    {
        $this->propertyScanUrlLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPropertyScan(): ?PropertyScan
    {
        return $this->propertyScan;
    }

    public function setPropertyScan(?PropertyScan $propertyScan): self
    {
        $this->propertyScan = $propertyScan;

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
            $propertyScanUrlLog->setPropertyScanUrl($this);
        }

        return $this;
    }

    public function removePropertyScanUrlLog(PropertyScanUrlLog $propertyScanUrlLog): self
    {
        if ($this->propertyScanUrlLogs->contains($propertyScanUrlLog)) {
            $this->propertyScanUrlLogs->removeElement($propertyScanUrlLog);
            // set the owning side to null (unless already changed)
            if ($propertyScanUrlLog->getPropertyScanUrl() === $this) {
                $propertyScanUrlLog->setPropertyScanUrl(null);
            }
        }

        return $this;
    }
}
