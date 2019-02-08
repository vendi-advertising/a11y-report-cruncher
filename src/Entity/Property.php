<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropertyRepository")
 */
class Property
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
    private $RootUrl;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PropertyScan", mappedBy="propertyId", orphanRemoval=true)
     */
    private $propertyScans;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $dateTimeCreated;

    public function __construct()
    {
        $this->propertyScans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRootUrl(): ?string
    {
        return $this->RootUrl;
    }

    public function setRootUrl(string $RootUrl): self
    {
        $this->RootUrl = $RootUrl;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection|PropertyScan[]
     */
    public function getPropertyScans(): Collection
    {
        return $this->propertyScans;
    }

    public function addPropertyScan(PropertyScan $propertyScan): self
    {
        if (!$this->propertyScans->contains($propertyScan)) {
            $this->propertyScans[] = $propertyScan;
            $propertyScan->setPropertyId($this);
        }

        return $this;
    }

    public function removePropertyScan(PropertyScan $propertyScan): self
    {
        if ($this->propertyScans->contains($propertyScan)) {
            $this->propertyScans->removeElement($propertyScan);
            // set the owning side to null (unless already changed)
            if ($propertyScan->getPropertyId() === $this) {
                $propertyScan->setPropertyId(null);
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
