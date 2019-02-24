<?php

namespace App\Entity\aXe;

use App\Entity\ScanUrl;
use App\Entity\aXe\RuleResults\RuleResultBase;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\aXe\ScanResultRepository")
 */
class ScanResult
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\aXe\RuleResults\RuleResultBase", mappedBy="scanResult", orphanRemoval=true)
     */
    private $results;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ScanUrl", inversedBy="scanResult", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $scanUrl;

    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|RuleResultBase[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(RuleResultBase $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setScanResult($this);
        }

        return $this;
    }

    public function removeResult(RuleResultBase $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getScanResult() === $this) {
                $result->setScanResult(null);
            }
        }

        return $this;
    }

    public function getScanUrl(): ?ScanUrl
    {
        return $this->scanUrl;
    }

    public function setScanUrl(ScanUrl $scanUrl): self
    {
        $this->scanUrl = $scanUrl;

        return $this;
    }
}
